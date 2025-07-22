<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\LeadOrigin;
use App\Models\Doctrine\OpportunityStatus;

class LeadOriginAnalysisController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe o relatório de análise de origem de leads, otimizado com DQL.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obter os repositórios
        $leadOriginRepository = $this->entityManager->getRepository(LeadOrigin::class);
        $opportunityStatusRepository = $this->entityManager->getRepository(OpportunityStatus::class);

        // Buscar todas as origens de leads para garantir que todas apareçam no relatório
        $allLeadOrigins = $leadOriginRepository->findAll();
        $reportData = [];

        // Inicializar os dados do relatório para cada origem de lead
        foreach ($allLeadOrigins as $origin) {
            $reportData[$origin->getOrigin()] = [
                'origin_name' => $origin->getOrigin(),
                'total_opportunities' => 0,
                'closed_sales' => 0,
            ];
        }

        // 1. DQL para contar o total de oportunidades por origem de lead
        $qbTotalOpportunities = $this->entityManager->createQueryBuilder();
        $qbTotalOpportunities
            ->select('IDENTITY(o.lead_origin_id) as leadOriginId', 'COUNT(o.opportunity_id) as totalOpportunities')
            ->from(Opportunity::class, 'o')
            ->groupBy('leadOriginId');

        $totalOpportunitiesResults = $qbTotalOpportunities->getQuery()->getArrayResult();

        // Mapear os resultados para o reportData
        foreach ($totalOpportunitiesResults as $result) {
            $leadOrigin = $leadOriginRepository->find($result['leadOriginId']);
            if ($leadOrigin) {
                $reportData[$leadOrigin->getOrigin()]['total_opportunities'] = (int) $result['totalOpportunities'];
            }
        }

        // 2. DQL para contar as vendas fechadas ('Ganho') por origem de lead
        $ganhoStatus = $opportunityStatusRepository->findOneBy(['status' => 'Ganho']);
        $ganhoStatusId = $ganhoStatus ? $ganhoStatus->getOpportunityStatusId() : null;

        if ($ganhoStatusId !== null) {
            $qbClosedSales = $this->entityManager->createQueryBuilder();
            $qbClosedSales
                ->select('IDENTITY(o.lead_origin_id) as leadOriginId', 'COUNT(o.opportunity_id) as closedSales')
                ->from(Opportunity::class, 'o')
                ->where('IDENTITY(o.fk_op_status_id) = :ganhoStatusId')
                ->setParameter('ganhoStatusId', $ganhoStatusId)
                ->groupBy('leadOriginId');

            $closedSalesResults = $qbClosedSales->getQuery()->getArrayResult();

            // Mapear os resultados para o reportData
            foreach ($closedSalesResults as $result) {
                $leadOrigin = $leadOriginRepository->find($result['leadOriginId']);
                if ($leadOrigin) {
                    $reportData[$leadOrigin->getOrigin()]['closed_sales'] = (int) $result['closedSales'];
                }
            }
        }

        // Converter o array associativo para um array indexado para a vista
        $finalReportData = array_values($reportData);

        return view('reports.lead_origin_analysis', compact('finalReportData'));
    }
}


