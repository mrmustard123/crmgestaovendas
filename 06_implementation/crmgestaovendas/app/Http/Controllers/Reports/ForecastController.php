<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Models\Opportunity; //para eloquent
use App\Models\Doctrine\Opportunity;
//use App\Models\OpportunityStatus; //para eloquent
use App\Models\Doctrine\OpportunityStatus;
//use App\Models\Stage;
use App\Models\Doctrine\Stage;
//use App\Models\StageHistory;
use App\Models\Doctrine\StageHistory;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;


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
            abort(404, 'Estado "Aberto" não encontrado no banco de dados');
        }
        
        // Sección 1: Pipeline Temporal
        $period = $request->input('period', 3); // Default 3 meses
        $pipelineData = $this->getPipelineData($openStatus, $period);
        
        // Sección 2: Análisis por Etapa
        $stageAnalysis = $this->getStageAnalysis($openStatus);
        
        return view('reports.forecast', [
            'pipelineData' => $pipelineData,
            'stageAnalysis' => $stageAnalysis,
            'selectedPeriod' => $period
        ]);
    }
    
    
    protected function getPipelineData(OpportunityStatus $openStatus, int $months)
    {
        $endDate = Carbon::now()->addMonths($months);

        
        /*Original: da un error en DATE_FORMAT*/
       /*
        $query0 = $this->entityManager->createQueryBuilder()
            ->select([
                "o.expected_closing_date as month",//<-- Esto no funciona por que necesita mes/anio para hacer el group by
                //"DATE_FORMAT(o.expected_closing_date, '%b/%Y') as month", <-- DA ERROR DATE_FORMAT
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
            
        $result0 = $query0->getResult();        
        */
        
        /*Opción 1: Usar Native SQL Query */
        // Usar Native SQL para funciones específicas de MySQL           
        $sql = "
            SELECT 
                DATE_FORMAT(o.expected_closing_date, '%b/%Y') as month,
                SUM(o.estimated_sale) as total_value,
                COUNT(o.opportunity_id) as opportunity_count
            FROM opportunity o
            WHERE o.fk_op_status_id = :status
            AND o.expected_closing_date IS NOT NULL
            AND o.expected_closing_date <= :endDate
            GROUP BY DATE_FORMAT(o.expected_closing_date, '%b/%Y')
	    ORDER BY DATE_FORMAT(o.expected_closing_date, '%b/%Y')
        ";

        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare($sql);
        $status = $openStatus->getOpportunityStatusId();
        $endDate = $endDate->format('Y-m-d');
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':endDate', $endDate);
   
        $result = collect($stmt->executeQuery()->fetchAllAssociative());

        return $result;
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
            // Native SQL para contar oportunidades por etapa
            $countSql = "
                SELECT COUNT(DISTINCT o.opportunity_id) 
                FROM opportunity o
                WHERE o.fk_op_status_id = :status
                AND EXISTS (
                    SELECT 1 FROM stage_history sh1 
                    WHERE sh1.fk_opportunity = o.opportunity_id 
                    AND sh1.fk_stage = :stageId
                    AND sh1.stage_hist_date = (
                        SELECT MAX(sh2.stage_hist_date) 
                        FROM stage_history sh2 
                        WHERE sh2.fk_opportunity = o.opportunity_id
                    )
                )
            ";

            $connection = $this->entityManager->getConnection();
            $stmt = $connection->prepare($countSql);
            $stmt->bindValue(':status', $openStatus->getOpportunityStatusId());
            $stmt->bindValue(':stageId', $stage->getStageId());
            $opportunityCount = $stmt->executeQuery()->fetchOne();

            // Native SQL para sumar valores por etapa
            $sumSql = "
                SELECT COALESCE(SUM(o.estimated_sale), 0) 
                FROM opportunity o
                WHERE o.fk_op_status_id = :status
                AND EXISTS (
                    SELECT 1 FROM stage_history sh1 
                    WHERE sh1.fk_opportunity = o.opportunity_id 
                    AND sh1.fk_stage = :stageId
                    AND sh1.stage_hist_date = (
                        SELECT MAX(sh2.stage_hist_date) 
                        FROM stage_history sh2 
                        WHERE sh2.fk_opportunity = o.opportunity_id
                    )
                )
            ";

            $stmt = $connection->prepare($sumSql);
            $stmt->bindValue(':status', $openStatus->getOpportunityStatusId());
            $stmt->bindValue(':stageId', $stage->getStageId());
            $totalValue = $stmt->executeQuery()->fetchOne();

            $stageAnalysis[] = [
                'stage_name' => $stage->getStageName(),
                'color_hex' => $stage->getColorHex(),
                'total_value' => (float) $totalValue,
                'opportunity_count' => (int) $opportunityCount,
                'probability' => $defaultProbabilities[$stage->getStageName()] ?? 0
            ];
        }

        return $stageAnalysis;
    }
    
   /* 
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
                )') //DOCTRINE NO SOPORTA LIMIT!!!
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
    */
}