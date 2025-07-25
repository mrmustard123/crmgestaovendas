<?php

namespace App\Http\Controllers\salesperson;

use App\Http\Controllers\Controller;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\Person;
use App\Models\Doctrine\PersonRole;
use App\Models\Doctrine\OpportunityStatus; // Importar la clase OpportunityStatus
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use DateTimeImmutable; // Para los campos de fecha y hora

class VendorOpportunitiesController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor para inyectar el EntityManager.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe a lista de Leads associados a um Vendor específico e outras oportunidades.
     *
     * @param int $vendorId O ID do Vendor.
     * @return View
     */
    public function showVendorOpportunities(): View
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
        


        // --- Lógica para buscar os Leads (já existente) ---
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);

        $queryBuilderLeads = $opportunityRepository->createQueryBuilder('o')
            ->join('o.person', 'p')
            ->join('p.personRole', 'pr')
            ->where('o.vendor = :vendor')
            ->andWhere('pr.role_name = :roleName')
            ->setParameter('vendor', $vendor)
            ->setParameter('roleName', 'Lead');

        $opportunitiesLeads = $queryBuilderLeads->getQuery()->getResult();

        $leads = [];
        foreach ($opportunitiesLeads as $opportunity) {
            $person = $opportunity->getPerson();
            if ($person) {
                $leads[] = [
                    'opportunity_name' => $opportunity->getOpportunityName(),
                    'person_id' => $person->getPersonId(),
                    'person_name' => $person->getPersonName(),
                    'main_email' => $person->getMainEmail(),
                    'main_phone' => $person->getMainPhone(),
                    'priority' => $opportunity->getPriority(),
                    'estimated_sale' => $opportunity->getEstimatedSale(),
                    'expected_closing_date' => $opportunity->getExpectedClosingDate() ? $opportunity->getExpectedClosingDate()->format('d/m/Y') : 'N/A',
                ];
            }
        }

        // --- NOVA Lógica para buscar TODAS as Oportunidades do Vendedor ---
        $queryBuilderAllOpportunities = $opportunityRepository->createQueryBuilder('o')
            ->leftJoin('o.fk_op_status_id', 'os') // LEFT JOIN com OpportunityStatus
            ->leftJoin('o.person', 'p_all') // LEFT JOIN com Person para obter o nome, mesmo que não seja um Lead
            ->where('o.vendor = :vendor')
            ->setParameter('vendor', $vendor);

        $allOpportunities = $queryBuilderAllOpportunities->getQuery()->getResult();

        $vendorOpportunities = [];
        foreach ($allOpportunities as $opportunity) {
            $statusName = $opportunity->getOpportunityStatus() ? $opportunity->getOpportunityStatus()->getStatus() : 'N/A';
            $personName = $opportunity->getPerson() ? $opportunity->getPerson()->getPersonName() : 'N/A';

            $vendorOpportunities[] = [
                'opportunity_id' => $opportunity->getOpportunityId(),
                'opportunity_name' => $opportunity->getOpportunityName(),
                'open_date' => $opportunity->getOpenDate() ? $opportunity->getOpenDate()->format('d/m/Y') : 'N/A',
                'opportunity_status' => $statusName,
                'associated_person' => $personName,
            ];
        }

        // Retorna a vista 'vendor_leads' passando o objeto Vendor, o array de leads e o array de todas as oportunidades
        return view('salesperson/vendors_opportunities', [
            'vendor' => $vendor,
            'leads' => $leads,
            'vendorOpportunities' => $vendorOpportunities, // Passando as novas oportunidades
        ]);
    }
}
