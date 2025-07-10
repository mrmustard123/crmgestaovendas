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
        
        
/*        
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);             
        $queryBuilder = $opportunityRepository->createQueryBuilder('o')
            ->leftJoin('o.vendor', 'v') // Une con la entidad Vendor
            ->leftJoin('o.opportunityStatus', 'os') // Une con la entidad OpportunityStatus
            ->leftJoin('o.person', 'p') // Une con la entidad Person (para mostrar nombre de la persona)
            ->leftJoin('p.company', 'c') // Une con la entidad Company (si la persona está asociada a una)
            ->leftJoin('o.leadOrigin', 'lo') // Une con la entidad LeadOrigin
            // Hacemos join con StageHistory si tenés una relación bidireccional en StageHistory
            ->leftJoin(StageHistory::class, 's', 'WITH', 's.opportunity = o')
            //->leftJoin('s.stage', 'st') // si necesitás el stage al que pertenece
            ->addSelect('v', 'os', 'p', 'c', 'lo', 's', 'st') // Selecciona también las entidades relacionadas para evitar N+1 queries
            ->where('v.vendor_id = :vendorId'); // Filtra por el ID del vendedor     
      */  
        
  
$queryBuilder = $this->entityManager->createQueryBuilder();        
$queryBuilder->select('o.opportunity_id AS opportunityId', 'o.opportunity_name AS opportunityName', 'IDENTITY(s.stage) AS stageId')
    ->from(Opportunity::class, 'o')
    ->leftJoin(StageHistory::class, 's', 'WITH', 's.opportunity = o')
    ->where('o.vendor = :vendorId')
    ->setParameter('vendorId', $vendor->getVendorId());

$opportunities = $queryBuilder->getQuery()->getScalarResult();




/*
$queryBuilder->select('o', 'v', 'os', 'p', 'c', 'lo', 's', 'st')        
    ->from(Opportunity::class, 'o')
    ->leftJoin('o.vendor', 'v')
    ->leftJoin('o.opportunityStatus', 'os')
    ->leftJoin('o.person', 'p')
    ->leftJoin('p.company', 'c')
    ->leftJoin('o.leadOrigin', 'lo')    
    //Esta línea reemplaza el SQL que mencionaste
    ->leftJoin(StageHistory::class, 's', 'WITH', 's.opportunity = o')
    ->leftJoin('s.stage', 'st') // si querés acceder al nombre de la etapa, etc.
    ->where('v.vendor_id = :vendorId');
     */   
       
        
        // Solo aplica el filtro de exclusión si hay IDs de estados finales válidos
        if (!empty($excludedStatusIds) && !in_array(0, $excludedStatusIds)) { // Excluye el [0] si lo usamos como placeholder
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('os.opportunity_status_id', ':excludedStatusIds'))
                         ->setParameter('excludedStatusIds', $excludedStatusIds);
        }
/*
        $opportunities = ($queryBuilder
            ->setParameter('vendorId', $vendor->getVendorId())
            ->orderBy('o.opportunity_name', 'ASC')
            ->getQuery()
            ->getResult());
*/
        // 5. Pasar las oportunidades a la vista
        return view('salesperson.myopportunities', compact('opportunities'));
    }
    
    public function updateStage(Request $request, Opportunity $opportunity)
    {
        $newStageId = $request->input('stage_id');

        if (!$newStageId) {
            return response()->json(['message' => 'ID de etapa no proporcionado.'], 400);
        }

        // Recuperar la etapa (Stage)
        $newStage = $this->entityManager->getRepository(Stage::class)->find($newStageId);

        if (!$newStage) {
            return response()->json(['message' => 'Etapa no encontrada.'], 404);
        }

        // Encontrar o crear la entrada en StageHistory para esta oportunidad
        // Como StageHistory no guarda histórico sino la etapa actual, buscamos la entrada existente
        $stageHistory = $this->entityManager->getRepository(StageHistory::class)->findOneBy(['opportunity' => $opportunity->getOpportunityId()]);

        if (!$stageHistory) {
            // Si no existe, crear una nueva entrada (esto podría pasar si una oportunidad se crea sin etapa inicial)
            $stageHistory = new StageHistory();
            $stageHistory->setOpportunity($opportunity);
            // También necesitarás establecer stage_hist_date, quizás con la fecha actual
            $stageHistory->setStageHistDate(new \DateTime()); // Usar DateTime para date type
        }

        // Actualizar la etapa
        $stageHistory->setStage($newStage);
        // Opcional: Actualizar el updated_at si no confías en el Lifecycle Callback
        // $stageHistory->setUpdatedAt(new \DateTimeImmutable()); // Si usas DateTimeImmutable

        try {
            $this->entityManager->persist($stageHistory);
            $this->entityManager->flush();
            return response()->json(['message' => 'Etapa de oportunidad actualizada con éxito!', 'opportunity_id' => $opportunity->getOpportunityId(), 'new_stage_id' => $newStageId]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al guardar la etapa: ' . $e->getMessage()], 500);
        }
    }    
    
    
}