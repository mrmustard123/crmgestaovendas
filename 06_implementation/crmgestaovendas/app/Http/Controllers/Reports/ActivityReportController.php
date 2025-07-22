<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Activity;
use DateTime; // Para trabalhar com as datas de entrada


class ActivityReportController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe o formulário de filtro e o relatório de atividades, otimizado com DQL.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $reportData = [];
        $startDate = null;
        $endDate = null;

        // Validar e processar as datas do formulário
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        if ($request->filled('start_date')) {
            $startDate = new DateTime($request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $endDate = new DateTime($request->input('end_date'));
            // Ajustar a data final para incluir todo o dia
            $endDate->setTime(23, 59, 59);
        }

        // Definir os tipos de atividade esperados para garantir que todos apareçam no relatório
        $expectedActivityTypes = ['Call', 'Meeting', 'Email'];

        // Inicializar os contadores para cada tipo de atividade com zero
        $activityCounts = array_fill_keys($expectedActivityTypes, 0);

        // Se ambas as datas forem fornecidas, gerar o relatório usando DQL
        if ($startDate && $endDate) {
            $qb = $this->entityManager->createQueryBuilder();
            $qb->select('a.activity_type', 'COUNT(a.activity_id) as activityCount')
               ->from(Activity::class, 'a')
               ->where('a.activity_date BETWEEN :startDate AND :endDate')
               ->setParameter('startDate', $startDate)
               ->setParameter('endDate', $endDate)
               ->groupBy('a.activity_type');

            $results = $qb->getQuery()->getArrayResult();

            // Mesclar os resultados da consulta com os contadores inicializados
            foreach ($results as $result) {
                $type = $result['activity_type'];
                if (array_key_exists($type, $activityCounts)) {
                    $activityCounts[$type] = (int) $result['activityCount'];
                }
            }
        }

        // Preparar os dados para a exibição na tabela
        foreach ($activityCounts as $type => $count) {
            $reportData[] = [
                'activity_type' => $type,
                'count' => $count,
            ];
        }

        return view('reports.activity_report', compact('reportData', 'startDate', 'endDate'));
    }
}

