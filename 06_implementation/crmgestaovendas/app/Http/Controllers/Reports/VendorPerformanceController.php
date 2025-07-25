<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\OpportunityStatus;

class VendorPerformanceController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe o relatório de desempenho por vendedor, otimizado com DQL.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obter os repositórios
        $vendorRepository = $this->entityManager->getRepository(Vendor::class);
        $opportunityStatusRepository = $this->entityManager->getRepository(OpportunityStatus::class);

        // Buscar todos os vendedores para garantir que todos apareçam no relatório
        $allVendors = $vendorRepository->findAll();
        $reportData = [];

        // Inicializar os dados do relatório para cada vendedor
        foreach ($allVendors as $vendor) {
            $reportData[$vendor->getVendorId()] = [
                'vendor_id' => $vendor->getVendorId(), // Incluir o ID do vendedor para o link
                'vendor_name' => $vendor->getVendorName(),
                'total_opportunities' => 0,
                'closed_won_opportunities' => 0,
                'total_revenue' => 0.00,
            ];
        }

        // Buscar o status "Ganho" para filtrar oportunidades
        $ganhoStatus = $opportunityStatusRepository->findOneBy(['status' => 'Ganho']);
        $ganhoStatusId = $ganhoStatus ? $ganhoStatus->getOpportunityStatusId() : null;

        // 1. DQL para contar o total de oportunidades e a receita total por vendedor
        $qbTotal = $this->entityManager->createQueryBuilder();
        $qbTotal->select(
                'IDENTITY(o.vendor) as vendorId',
                'COUNT(o.opportunity_id) as totalOpportunities',
                'SUM(o.final_price) as totalRevenue'
            )
            ->from(Opportunity::class, 'o')
            ->groupBy('vendorId');

        $totalResults = $qbTotal->getQuery()->getArrayResult();

        // Mapear os resultados para o reportData
        foreach ($totalResults as $result) {
            $vendorId = $result['vendorId'];
            if (isset($reportData[$vendorId])) {
                $reportData[$vendorId]['total_opportunities'] = (int) $result['totalOpportunities'];
                $reportData[$vendorId]['total_revenue'] = (float) $result['totalRevenue'];
            }
        }

        // 2. DQL para contar as oportunidades fechadas ('Ganho') por vendedor
        if ($ganhoStatusId !== null) {
            $qbClosedWon = $this->entityManager->createQueryBuilder();
            $qbClosedWon->select(
                    'IDENTITY(o.vendor) as vendorId',
                    'COUNT(o.opportunity_id) as closedWonOpportunities'
                )
                ->from(Opportunity::class, 'o')
                ->where('IDENTITY(o.fk_op_status_id) = :ganhoStatusId')
                ->setParameter('ganhoStatusId', $ganhoStatusId)
                ->groupBy('vendorId');

            $closedWonResults = $qbClosedWon->getQuery()->getArrayResult();

            // Mapear os resultados para o reportData
            foreach ($closedWonResults as $result) {
                $vendorId = $result['vendorId'];
                if (isset($reportData[$vendorId])) {
                    $reportData[$vendorId]['closed_won_opportunities'] = (int) $result['closedWonOpportunities'];
                }
            }
        }

        // Converter o array associativo para um array indexado para a vista
        $finalReportData = array_values($reportData);

        // Ordenar os dados por receita gerada (opcional, mas bom para um ranking)
        usort($finalReportData, function ($a, $b) {
            return $b['total_revenue'] <=> $a['total_revenue'];
        });

        return view('reports.vendor_performance_report', compact('finalReportData'));
    }
}
