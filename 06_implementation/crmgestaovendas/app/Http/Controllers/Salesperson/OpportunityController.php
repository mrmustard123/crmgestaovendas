<?php

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

        // 3. Obtener los IDs de los estados de oportunidad "Ganho" (Won) y "Perdido" (Lost)
        $opportunityStatusRepository = $this->entityManager->getRepository(OpportunityStatus::class);
        $wonStatus = $opportunityStatusRepository->findOneBy(['status' => 'Won']);
        $lostStatus = $opportunityStatusRepository->findOneBy(['status' => 'Lost']);

        $excludedStatusIds = [];
        if ($wonStatus) {
            $excludedStatusIds[] = $wonStatus->getOpportunityStatusId();
        }
        if ($lostStatus) {
            $excludedStatusIds[] = $lostStatus->getOpportunityStatusId();
        }

        // Si no se encuentran los estados "Won" o "Lost", asumimos que todas son activas
        // o manejamos un error si es un requisito estricto.
        if (empty($excludedStatusIds)) {
             // Puedes loguear un warning o lanzar una excepción si estos estados son mandatorios
             Log::warning("OpportunityStatus 'Won' ou 'Lost' não encontrados. Todas as oportunidades serão exibidas.");
             // Para este caso, no excluimos nada, o puedes decidir redirigir con un error.
             // Para la tabla, si no hay estados a excluir, simplemente no aplicamos el filtro.
             $excludedStatusIds = [0]; // Usamos un ID que no existirá para que el NOT IN no filtre nada si no hay estados finales
        }


        // 4. Consultar las oportunidades del vendedor que NO estén cerradas/perdidas
        
      

        
    // Obtener todas las oportunidades del vendedor con su etapa actual
        
    $queryBuilder = $this->entityManager->createQueryBuilder();
    $queryBuilder->select(
            'o.opportunity_id AS opportunityId',
            'o.opportunity_name AS opportunityName',
            'IDENTITY(o.opportunityStage) AS stageId' // Usamos directamente la relación con Stage
        )
        ->from(Opportunity::class, 'o')
        ->where('o.vendor = :vendorId')
        ->setParameter('vendorId', $vendor->getVendorId());

        $opportunities = $queryBuilder->getQuery()->getScalarResult();        
  
        
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

    // Obtener la nueva etapa
    $newStage = $this->entityManager->getRepository(Stage::class)->find($newStageId);
    if (!$newStage) {
        return response()->json(['message' => 'Estagio não encontrado.'], 404);
    }

    // Actualizar directamente el stage en la oportunidad
    $opportunity->setOpportunityStage($newStage);

    try {
        $this->entityManager->persist($opportunity);
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
    
    
}