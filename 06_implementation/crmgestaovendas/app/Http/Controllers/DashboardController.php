<?php
/*
Author: Leonardo G. Tellez Saucedo
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\User;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\Activity;
use App\Models\Doctrine\OpportunityStatus;
use DateTime;
use DateTimeImmutable;

class DashboardController extends Controller
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
     * Exibe o dashboard principal com dados dinâmicos baseados no grupo do usuário.
     *
     * @return View
     */
    public function index(): View
    {
        $authenticatedUser = Auth::guard('web')->user();
        $userGroupName = '';
        $dashboardData = [];

        // Verifica se o usuário está autenticado e obtém seu grupo
        if ($authenticatedUser && $authenticatedUser->getUsersGroup()) {
            $userGroupName = $authenticatedUser->getUsersGroup()->getGroupName();
        }

        // Lógica para Vendedores
        if ($userGroupName == 'Vendedores') {
            // Assumimos que o usuário Vendedor está associado a um Vendor
            //$vendor = $authenticatedUser->getVendor(); // Método para obter o Vendor associado ao User
            $vendor = $this->entityManager->getRepository(Vendor::class)->findOneBy(['user' => $authenticatedUser]);
            if (!$vendor) {
                throw new \Exception("Authenticated user is not linked to a Vendor.");
            }

            if ($vendor) {
                // 1. Leads Registrados Hoje
                // Busca oportunidades do vendedor onde a pessoa é um 'Lead' e a oportunidade foi aberta hoje
                $today = (new DateTime())->format('Y-m-d');
                $queryBuilderLeadsToday = $this->entityManager->getRepository(Opportunity::class)->createQueryBuilder('o')
                    ->select('COUNT(o.opportunity_id)')
                    ->join('o.person', 'p')
                    ->join('p.personRole', 'pr')
                    ->where('o.vendor = :vendor')
                    ->andWhere('pr.role_name = :roleName')
                    ->andWhere('o.open_date = :todayDate')
                    ->setParameter('vendor', $vendor)
                    ->setParameter('roleName', 'Lead')
                    ->setParameter('todayDate', $today);
                $leadsTodayCount = $queryBuilderLeadsToday->getQuery()->getSingleScalarResult();
                $dashboardData['leadsTodayCount'] = $leadsTodayCount;
                // Exemplo de meta diária (pode vir de uma configuração ou do próprio Vendor)
                $dashboardData['dailyLeadGoal'] = 10;

                // 2. Atividades Pendentes
                // Conta atividades com status 'scheduled' associadas às oportunidades do vendedor
                $queryBuilderPendingActivities = $this->entityManager->getRepository(Activity::class)->createQueryBuilder('a')
                    ->select('COUNT(a.activity_id)')
                    ->join('a.opportunity', 'o')
                    ->where('o.vendor = :vendor')
                    ->andWhere('a.status = :activityStatus')
                    ->setParameter('vendor', $vendor)
                    ->setParameter('activityStatus', 'scheduled'); // Assumindo 'scheduled' como status de pendente
                $pendingActivitiesCount = $queryBuilderPendingActivities->getQuery()->getSingleScalarResult();
                $dashboardData['pendingActivitiesCount'] = $pendingActivitiesCount;

                // 3. Oportunidades em Andamento
                // Conta oportunidades com status 'Aberto' (ou similar)
                // Primeiro, encontra o ID do status 'Aberto'
                $opportunityStatusRepository = $this->entityManager->getRepository(OpportunityStatus::class);
                $openStatus = $opportunityStatusRepository->findOneBy(['status' => 'Aberto']);

                $opportunitiesInProgressCount = 0;
                $estimatedValueInProgress = 0.0;

                if ($openStatus) {
                    $queryBuilderOpportunitiesInProgress = $this->entityManager->getRepository(Opportunity::class)->createQueryBuilder('o')
                        ->select('COUNT(o.opportunity_id) as count_opportunities', 'SUM(o.estimated_sale) as sum_estimated_sale')
                        ->where('o.vendor = :vendor')
                        ->andWhere('o.fk_op_status_id = :statusId')
                        ->setParameter('vendor', $vendor)
                        ->setParameter('statusId', $openStatus->getOpportunityStatusId());

                    $result = $queryBuilderOpportunitiesInProgress->getQuery()->getSingleResult();
                    $opportunitiesInProgressCount = $result['count_opportunities'] ?? 0;
                    $estimatedValueInProgress = $result['sum_estimated_sale'] ?? 0.0;
                }

                $dashboardData['opportunitiesInProgressCount'] = $opportunitiesInProgressCount;
                $dashboardData['estimatedValueInProgress'] = $estimatedValueInProgress;

            } else {
                // Se o usuário vendedor não tiver um Vendor associado, os contadores serão 0
                $dashboardData['leadsTodayCount'] = 0;
                $dashboardData['dailyLeadGoal'] = 10;
                $dashboardData['pendingActivitiesCount'] = 0;
                $dashboardData['opportunitiesInProgressCount'] = 0;
                $dashboardData['estimatedValueInProgress'] = 0.0;
            }
        }

        // Lógica para Gerentes
        if ($userGroupName == 'Gerentes') {
            $currentMonth = (new DateTime())->format('Y-m');
            $lastMonth = (new DateTime())->modify('-1 month')->format('Y-m');

            // Oportunidades conquistadas neste mês
            // Assumimos que 'Conquistado' é um status final para oportunidades ganhas
            $opportunityStatusRepository = $this->entityManager->getRepository(OpportunityStatus::class);
            $wonStatus = $opportunityStatusRepository->findOneBy(['status' => 'Ganho']); // Assumindo 'Conquistado'

            $opportunitiesWonCurrentMonth = 0;
            $opportunitiesWonLastMonth = 0;

            if ($wonStatus) {
                // Contar oportunidades conquistadas no mês atual
                $queryBuilderCurrentMonth = $this->entityManager->getRepository(Opportunity::class)->createQueryBuilder('o')
                    ->select('COUNT(o.opportunity_id)')
                    ->where('o.fk_op_status_id = :statusId')
                    ->andWhere('SUBSTRING(o.expected_closing_date, 1, 7) = :currentMonth') // Assumindo que a data de fechamento é usada para "Ganho"
                    ->setParameter('statusId', $wonStatus->getStatus())
                    ->setParameter('currentMonth', $currentMonth);
                $opportunitiesWonCurrentMonth = $queryBuilderCurrentMonth->getQuery()->getSingleScalarResult();

                // Contar oportunidades conquistadas no mês passado
                $queryBuilderLastMonth = $this->entityManager->getRepository(Opportunity::class)->createQueryBuilder('o')
                    ->select('COUNT(o.opportunity_id)')
                    ->where('o.fk_op_status_id = :statusId')
                    ->andWhere('SUBSTRING(o.expected_closing_date, 1, 7) = :lastMonth')
                    ->setParameter('statusId', $wonStatus->getStatus())
                    ->setParameter('lastMonth', $lastMonth);
                $opportunitiesWonLastMonth = $queryBuilderLastMonth->getQuery()->getSingleScalarResult();
            }

            $percentageChange = 0;
            if ($opportunitiesWonLastMonth > 0) {
                $percentageChange = (($opportunitiesWonCurrentMonth - $opportunitiesWonLastMonth) / $opportunitiesWonLastMonth) * 100;
            } elseif ($opportunitiesWonCurrentMonth > 0) {
                $percentageChange = 100; // Se não havia no mês passado e há agora, é 100% de aumento
            }

            $dashboardData['opportunitiesWonCurrentMonth'] = $opportunitiesWonCurrentMonth;
            $dashboardData['opportunitiesWonLastMonth'] = $opportunitiesWonLastMonth;
            $dashboardData['percentageChange'] = round($percentageChange, 2); // Arredonda para 2 casas decimais
        }

        return view('dashboard', [
            'userGroupName' => $userGroupName,
            'dashboardData' => $dashboardData,
        ]);
    }
}
