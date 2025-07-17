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
               ->andWhere('sh.stage_hist_date >= :start AND sh.stage_hist_date <= :end')
               ->orderBy('sh.fk_stage')
               ->setParameter('won', 'won')
               ->setParameter('start', $startDate)
               ->setParameter('end', $endDate);

            $wonHistories = $qb->getQuery()->getResult();
            
            
            $qb1 = $this->entityManager->createQueryBuilder();
            $qb1->select('sh', 's')
               ->from(StageHistory::class, 'sh')
               ->join('sh.fk_stage', 's')
               ->where('sh.won_lost = :lost')
               ->andWhere('sh.stage_hist_date >= :start AND sh.stage_hist_date <= :end')
               ->orderBy('sh.fk_stage')
               ->setParameter('lost', 'lost')
               ->setParameter('start', $startDate)
               ->setParameter('end', $endDate);

            $lostHistories = $qb1->getQuery()->getResult();

            // Calcular total de oportunidades ganadas
            $totalWon = count($wonHistories);            

            // Calcular total de oportunidades perdidas
            $totalLost = count($lostHistories);
            
            if (($totalWon > 0) || ($totalLost > 0)) {
                // Agrupar por etapa
                $grouped = [];
                foreach ($wonHistories as $history) {
                    $stageName = $history->getStage()->getStageName();
                    if (!isset($grouped[$stageName])) {
                        $grouped[$stageName] = 0;
                    }
                    $grouped[$stageName]++;
                }
                
                $grouped1 = [];
                foreach ($lostHistories as $history) {
                    $stageName = $history->getStage()->getStageName();
                    if (!isset($grouped1[$stageName])) {
                        $grouped1[$stageName] = 0;
                    }
                    $grouped1[$stageName]++;
                }                
                
                // Calcular porcentajes won
                foreach ($grouped as $stageName => $count) {
                    $conversionRatesWon[$stageName] = (is_countable($totalWon))? 0:($count / $totalWon) * 100;                   
                }                                
                // Calcular porcentajes lost
                foreach ($grouped1 as $stageName => $count) {
                    $conversionRatesLost[$stageName] = (is_countable($totalLost))? 0:($count / $totalLost) * 100;                   
                }                
 
            }
        }
        
        $fechaOriginal = $startDate;
        $startDate = date('d/m/Y', strtotime($fechaOriginal));      
        
        $fechaOriginal = $endDate;
        $endDate = date('d/m/Y', strtotime($fechaOriginal));            
        
        return view('reports.sales_funnel', [
            'conversionRatesWon' => $conversionRatesWon ?? null,
            'conversionRatesLost' => $conversionRatesLost ?? null,
            'startDate' => $startDate,
            'endDate' =>  $endDate,   
            ]);    
    }
}