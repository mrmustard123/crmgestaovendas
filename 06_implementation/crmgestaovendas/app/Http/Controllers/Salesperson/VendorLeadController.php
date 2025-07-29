<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
namespace App\Http\Controllers\salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\Person;
use App\Models\Doctrine\PersonRole;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\View\View;

class VendorLeadController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor para injetar o EntityManager.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe a lista de Leads associados a um Vendor específico.
     *
     * @param int $vendorId O ID do Vendor.
     * @return View
     */
    public function showVendorLeads(string $role): View
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
        

        // Obtém o repositório da entidade Opportunity
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);

        // Constrói uma query DQL para buscar as oportunidades
        // que pertencem ao vendor e onde a pessoa associada tem o papel de 'Lead'.
        $queryBuilder = $opportunityRepository->createQueryBuilder('o')
            // Faz um JOIN com a entidade Person (p) através da propriedade 'person' da Opportunity
            ->join('o.person', 'p')
            // Faz um JOIN com a entidade PersonRole (pr) através da propriedade 'personRole' da Person
            ->join('p.personRole', 'pr')
            // Filtra as oportunidades pelo Vendor (o.vendor deve ser igual ao objeto $vendor encontrado)
            ->where('o.vendor = :vendor')
            // E filtra as oportunidades onde o role_name da PersonRole é 'Lead'
            ->andWhere('pr.role_name = :roleName')
            // Define o parâmetro :vendor com o objeto $vendor
            ->setParameter('vendor', $vendor)
            // Define o parâmetro :roleName com a string 'Lead'
            ->setParameter('roleName', $role);

        // Executa a query e obtém os resultados (coleção de objetos Opportunity)
        $opportunities = $queryBuilder->getQuery()->getResult();

        // Prepara um array para armazenar os dados dos leads de forma mais amigável para a vista
        $leads = [];
        foreach ($opportunities as $opportunity) {
            // Obtém a pessoa associada à oportunidade
            $person = $opportunity->getPerson();
            // Verifica se a pessoa existe (deve existir, dada a lógica da query)
            if ($person) {
                $leads[] = [
                    'opportunity_name' => $opportunity->getOpportunityName(),
                    'person_id' => $person->getPersonId(),
                    'person_name' => $person->getPersonName(),
                    'main_email' => $person->getMainEmail(),
                    'main_phone' => $person->getMainPhone(),
                    'priority' => $opportunity->getPriority(),
                    'estimated_sale' => $opportunity->getEstimatedSale(),
                    // Formata a data de fechamento esperada para 'dd/mm/yyyy' ou 'N/A' se for nula
                    'expected_closing_date' => $opportunity->getExpectedClosingDate() ? $opportunity->getExpectedClosingDate()->format('d/m/Y') : 'N/A',
                ];
            }
        }

        // Retorna a vista 'vendor_leads' passando o objeto Vendor e o array de leads
        return view('salesperson/vendor_leads', [
            'vendor' => $vendor,
            'leads' => $leads,
        ]);
    }
}
