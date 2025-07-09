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
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);

        $queryBuilder = $opportunityRepository->createQueryBuilder('o')
            ->leftJoin('o.vendor', 'v') // Une con la entidad Vendor
            ->leftJoin('o.opportunityStatus', 'os') // Une con la entidad OpportunityStatus
            ->leftJoin('o.person', 'p') // Une con la entidad Person (para mostrar nombre de la persona)
            ->leftJoin('p.company', 'c') // Une con la entidad Company (si la persona está asociada a una)
            ->leftJoin('o.leadOrigin', 'lo') // Une con la entidad LeadOrigin
            // Agrega leftJoin para Stage si lo usas directamente en Opportunity (aunque StageHistory es más común)
            // ->leftJoin('o.stage', 's') // Si Opportunity tiene una relación directa con Stage

            ->addSelect('v', 'os', 'p', 'c', 'lo') // Selecciona también las entidades relacionadas para evitar N+1 queries
            ->where('v.vendor_id = :vendorId'); // Filtra por el ID del vendedor

        // Solo aplica el filtro de exclusión si hay IDs de estados finales válidos
        if (!empty($excludedStatusIds) && !in_array(0, $excludedStatusIds)) { // Excluye el [0] si lo usamos como placeholder
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('os.opportunity_status_id', ':excludedStatusIds'))
                         ->setParameter('excludedStatusIds', $excludedStatusIds);
        }

        $opportunities = $queryBuilder
            ->setParameter('vendorId', $vendor->getVendorId())
            ->orderBy('o.opportunity_name', 'ASC')
            ->getQuery()
            ->getResult();

        // 5. Pasar las oportunidades a la vista
        return view('salesperson.myopportunities', compact('opportunities'));
    }
}