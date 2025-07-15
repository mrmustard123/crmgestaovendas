<?php
/*
File: sales_funnel_controller
Author: Leonardo G. Tellez Saucedo
Created on: 11 jul. de 2025 23:46:18
Email: leonardo616@gmail.com
*/



namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Doctrine\StageHistory;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class SalesFunnelController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $conversionRates = [];
        
        if ($startDate && $endDate) {
            // Crear el QueryBuilder para StageHistory
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('sh', 's')
               ->from(StageHistory::class, 'sh')
               ->join('sh.fk_stage', 's')
               ->where('sh.won_lost = :won')
               ->andWhere('sh.stage_hist_date BETWEEN :start AND :end')
               ->orderBy('sh.fk_stage')
               ->setParameter('won', 'won')
               ->setParameter('start', $startDate)
               ->setParameter('end', $endDate);

            $wonHistories = $qb->getQuery()->getResult();

            // Calcular total de oportunidades ganadas
            $totalWon = count($wonHistories);
            
            if ($totalWon > 0) {
                // Agrupar por etapa
                $grouped = [];
                foreach ($wonHistories as $history) {
                    $stageName = $history->getStage()->getStageName();
                    if (!isset($grouped[$stageName])) {
                        $grouped[$stageName] = 0;
                    }
                    $grouped[$stageName]++;
                }
                
                // Calcular porcentajes
                foreach ($grouped as $stageName => $count) {
                    $conversionRates[$stageName] = ($count / $totalWon) * 100;
                }
            }
        }
        
        return view('reports.sales_funnel', [
            'conversionRates' => $conversionRates ?? null,
        ]);
    }
}