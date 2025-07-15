<?php

namespace App\Http\Controllers;

use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\OpportunityStatus;
use App\Models\Doctrine\Stage;
use App\Models\Doctrine\StageHistory;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ForecastController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(Request $request)
    {
        // Obtener el estado "Aberto" con Doctrine
        $openStatus = $this->entityManager->getRepository(OpportunityStatus::class)
            ->findOneBy(['status' => 'Aberto']);
            
        if (!$openStatus) {
            abort(404, 'Estado "Aberto" no encontrado en la base de datos');
        }
        
        // Sección 1: Pipeline Temporal
        $period = $request->input('period', 3); // Default 3 meses
        $pipelineData = $this->getPipelineData($openStatus, $period);
        
        // Sección 2: Análisis por Etapa
        $stageAnalysis = $this->getStageAnalysis($openStatus);
        
        return view('forecast.index', [
            'pipelineData' => $pipelineData,
            'stageAnalysis' => $stageAnalysis,
            'selectedPeriod' => $period
        ]);
    }
    
    protected function getPipelineData(OpportunityStatus $openStatus, int $months)
    {
        $endDate = Carbon::now()->addMonths($months);
        
        $query = $this->entityManager->createQueryBuilder()
            ->select([
                "DATE_FORMAT(o.expected_closing_date, '%b/%Y') as month",
                'SUM(o.estimated_sale) as total_value',
                'COUNT(o.opportunity_id) as opportunity_count'
            ])
            ->from(Opportunity::class, 'o')
            ->where('o.fk_op_status_id = :status')
            ->andWhere('o.expected_closing_date IS NOT NULL')
            ->andWhere('o.expected_closing_date <= :endDate')
            ->setParameter('status', $openStatus->getOpportunityStatusId())
            ->setParameter('endDate', $endDate->format('Y-m-d'))
            ->groupBy('month')
            ->orderBy('o.expected_closing_date')
            ->getQuery();
            
        return $query->getResult();
    }
    
    protected function getStageAnalysis(OpportunityStatus $openStatus)
    {
        // Obtener probabilidades por defecto desde configuración
        $defaultProbabilities = [
            'Apresentação' => 15,
            'Proposta' => 35,
            'Negociação' => 60
        ];
        
        // Obtener todas las etapas
        $stages = $this->entityManager->getRepository(Stage::class)->findAll();
        
        $stageAnalysis = [];
        
        foreach ($stages as $stage) {
            // Consulta para contar oportunidades por etapa
            $countQuery = $this->entityManager->createQueryBuilder()
                ->select('COUNT(o.opportunity_id)')
                ->from(Opportunity::class, 'o')
                ->where('o.fk_op_status_id = :status')
                ->andWhere('EXISTS (
                    SELECT sh FROM App\Models\Doctrine\StageHistory sh 
                    WHERE sh.fk_opportunity = o.opportunity_id 
                    AND sh.fk_stage = :stageId
                    ORDER BY sh.stage_hist_date DESC
                    LIMIT 1
                )')
                ->setParameter('status', $openStatus->getOpportunityStatusId())
                ->setParameter('stageId', $stage->getStageId())
                ->getQuery();
                
            $opportunityCount = $countQuery->getSingleScalarResult();
            
            // Consulta para sumar valores por etapa
            $sumQuery = $this->entityManager->createQueryBuilder()
                ->select('SUM(o.estimated_sale)')
                ->from(Opportunity::class, 'o')
                ->where('o.fk_op_status_id = :status')
                ->andWhere('EXISTS (
                    SELECT sh FROM App\Models\Doctrine\StageHistory sh 
                    WHERE sh.fk_opportunity = o.opportunity_id 
                    AND sh.fk_stage = :stageId
                    ORDER BY sh.stage_hist_date DESC
                    LIMIT 1
                )')
                ->setParameter('status', $openStatus->getOpportunityStatusId())
                ->setParameter('stageId', $stage->getStageId())
                ->getQuery();
                
            $totalValue = $sumQuery->getSingleScalarResult() ?? 0;
            
            $stageAnalysis[] = [
                'stage_name' => $stage->getStageName(),
                'total_value' => $totalValue,
                'opportunity_count' => $opportunityCount,
                'probability' => $defaultProbabilities[$stage->getStageName()] ?? 0
            ];
        }
        
        return $stageAnalysis;
    }
}