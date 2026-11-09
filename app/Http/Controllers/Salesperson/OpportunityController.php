<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\OpportunityStatus;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\User; 
use App\Models\Doctrine\Stage; 
use App\Models\Doctrine\StageHistory; 
use Illuminate\Support\Facades\Log;

class OpportunityController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra una lista de oportunidades para el vendedor actual.
     * Solo incluye oportunidades que no están en estado 'Won' o 'Lost'.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function myopportunities()
    {
        // 1. Obtener el usuario autenticado (entidad User de Doctrine, si tu guard lo devuelve)
        // Asumiendo que Auth::user() devuelve una instancia de App\Models\Doctrine\User
        $currentAuthUser = Auth::user();

        if (!$currentAuthUser) {
            // Esto no debería ocurrir si el middleware 'auth' está funcionando,
            // pero es una buena práctica de seguridad.
            return redirect()->route('login')->with('error', 'Usuário não autenticado.');
        }

        // 2. Obtener la entidad Vendor asociada al usuario autenticado
        // Usamos el método getUser() del Vendor para buscar por la entidad User
        $vendorRepository = $this->entityManager->getRepository(Vendor::class);
        $vendor = $vendorRepository->findOneBy(['user' => $currentAuthUser]);

        if (!$vendor) {
            return redirect()->back()->with('error', 'Seu usuário não está vinculado a um vendedor. Entre em contato com o administrador.');
        }

  


    // 3. Consultar todas las oportunidades del vendedor con su historial de etapas
    // donde won_lost sea NULL (oportunidades activas/en proceso) pero solo las ultimas etapas
        
        $queryBuilder = $this->entityManager->createQueryBuilder();        

        $queryBuilder->select('sh')
            ->from(StageHistory::class, 'sh')
            ->join('sh.fk_opportunity', 'o')
            ->join('sh.fk_stage', 's')
            ->where('o.vendor = :vendorId')
            ->andWhere('sh.won_lost IS NULL')
            ->andWhere('NOT EXISTS (
                SELECT sh2.stage_hist_id 
                FROM App\Models\Doctrine\StageHistory sh2 
                WHERE sh2.fk_opportunity = sh.fk_opportunity 
                AND sh2.fk_stage > sh.fk_stage 
                AND sh2.won_lost IS NULL
            )')
            ->setParameter('vendorId', $vendor->getVendorId())
            ->orderBy('sh.fk_opportunity', 'ASC');
            $opportunities = $queryBuilder->getQuery()->getResult();        
  
        
        // 5. Pasar las oportunidades a la vista
        return view('salesperson.myopportunities', compact('opportunities'));
    }
    
public function updateStage(Request $request, $id)
{
    $newStageId = $request->input('stage_id');

    // Validación básica
    if (!$newStageId) {
        return response()->json(['message' => 'ID da estagio não fornecido.'], 400);
    }

    // Obtener la oportunidad
    $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($id);
    if (!$opportunity) {
        return response()->json(['message' => 'Oportunidade não encontrada.'], 404);
    }
    
    
    $newStage = $this->entityManager->getRepository(Stage::class)->find($newStageId);
    if (!$newStage) {
        return response()->json(['message' => 'Estagio não encontrado.'], 404);
    }

    // Crear la nueva etapa
    $newStageHistory = new StageHistory();
    $newStageHistory->setStageHistDate(new \DateTime());
    $newStageHistory->setComments('Oportunidade movida para novo estagio');
    $newStageHistory->setOpportunity($opportunity);
    $newStageHistory->setStage($newStage);
            
    try {
        $this->entityManager->persist($newStageHistory);
        $this->entityManager->flush();
        
        return response()->json([
            'message' => 'Estagio atualizado com sucesso!',
            'opportunity_id' => $id,
            'new_stage_id' => $newStageId
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao atualizar estágio: ' . $e->getMessage()
        ], 500);
    }
    
    } 
    
/*
Estas últimas 4 columnas son botones. Cada botón tiene una función:
Perdido: Señala que la oportunidad fue perdida, se actualiza la última StageHistory 
cambiando de NULL a "lost" el campo won_lost y además la Opportunity se pone con status "Perdido".
Ganho: Similar a Perdido, pero ya te imaginarás los cambios el los campos.
Atividades: Este botón abre un formulario para registrar una nueva actividad relacionada con la oportunidad de esa fila.
Documentos: Este botón sirve para hacer el upload de documentos relacionados a la oportunidad de esa fila.
 */  
    
    public function markAsLost(Request $request, $opportunityId)
    {
        $stageHistoryId = $request->input('stage_history_id');

        // 1. Actualizar StageHistory
        $stageHistory = $this->entityManager->getRepository(StageHistory::class)->find($stageHistoryId);
        $stageHistory->setWonLost('lost');

        // 2. Actualizar OpportunityStatus
        $lostStatus = $this->entityManager->getRepository(OpportunityStatus::class)
                         ->findOneBy(['status' => 'Perdido']);

        if ($lostStatus) {
            $opportunity = $stageHistory->getOpportunity();
            $opportunity->setOpportunityStatus($lostStatus);
        }

        $this->entityManager->flush();

        return response()->json([
            'message' => 'Oportunidade marcada como perdida!',
            'opportunity_id' => $opportunityId
        ]);
    }       
    
    
    
    public function markAsWon(Request $request, $opportunityId)
    {
        $stageHistoryId = $request->input('stage_history_id');

        // 1. Actualizar StageHistory
        $stageHistory = $this->entityManager->getRepository(StageHistory::class)->find($stageHistoryId);
        $stageHistory->setWonLost('won');

        // 2. Actualizar OpportunityStatus (asumiendo que tienes un repositorio)
        $wonStatus = $this->entityManager->getRepository(OpportunityStatus::class)
                        ->findOneBy(['status' => 'Ganho']);

        if ($wonStatus) {
            $opportunity = $stageHistory->getOpportunity();
            $opportunity->setOpportunityStatus($wonStatus);
        }

        $this->entityManager->flush();

        return response()->json([
            'message' => 'Oportunidade marcada como ganha!',
            'opportunity_id' => $opportunityId
        ]);
    }                   

 
    
    
}