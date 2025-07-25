<?php
/*
Author: Leonardo G. Tellez Saucedo
Email: leonardo616@gmail.com
*/
?>
@extends('layouts.app')

@section('page_title', 'Relatório de Desempenho por Vendedor')

@section('content')


        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            Relatório de Desempenho por Vendedor
        </h1>
                

        @if (count($finalReportData) > 0)
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome do Vendedor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Oportunidades Criadas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Negócios Fechados (Ganho)
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Receita Gerada
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($finalReportData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">                                    
                                    <a href="{{ route('reports.vendors.edit', ['vendorId' => $data['vendor_id']]) }}" class="hover:underline">
                                        {{ $data['vendor_name'] }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $data['total_opportunities'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $data['closed_won_opportunities'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{-- Formato para moeda brasileira (BRL) --}}
                                    {{ number_format($data['total_revenue'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 text-lg">
                Nenhum dado de relatório disponível.
            </p>
        @endif
@endsection
