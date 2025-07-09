<?php
/*
File: myopportunities.blade.php
Author: Leonardo Tellez
Purpose: Display a list of current vendor's opportunities.
*/
?>

@extends('layouts.app')

@section('page_title', 'Minhas Oportunidades')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Minhas Oportunidades</h2>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome da Oportunidade
                            </th>
                            <!--th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lead
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Empresa
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Atual
                            </th-->
                            {{-- Columnas Kanban (inicialmente placeholders) --}}
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Apresentação
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Proposta
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Negociação
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ganho/Perdido
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nova Atividade
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Documentos
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($opportunities as $opportunity)
                            <tr>
                                <td class="py-4 px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $opportunity->getOpportunityName() }}
                                </td>
                                <!--td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700">
                                    {{ $opportunity->getPerson() ? $opportunity->getPerson()->getPersonName() : 'N/A' }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700">
                                    {{ $opportunity->getPerson() && $opportunity->getPerson()->getCompany() ? $opportunity->getPerson()->getCompany()->getSocialReason() : 'N/A' }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 text-center">
                                    {{ $opportunity->getOpportunityStatus() ? $opportunity->getOpportunityStatus()->getStatus() : 'N/A' }}
                                </td-->

                                {{-- Columnas Kanban (placeholders por ahora) --}}
                                {{-- NOTA: La lógica para determinar la etapa actual de la Oportunidad
                                        debería venir de la relación con StageHistory.
                                        Por simplicidad inicial, estamos asumiendo que Opportunity
                                        tiene un getStage() o que obtendremos la etapa actual de StageHistory.
                                        Si Opportunity no tiene un getStage() directo, esto requerirá
                                        una consulta adicional o ajustar el controlador para traer la etapa actual.
                                        Por ahora, se usa getOpportunityStatus()->getStatus() como marcador.
                                --}}
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 text-center">
                                    <div class="kanban-card p-2 rounded-md {{ $opportunity->getOpportunityStatus()->getStatus() == 'Apresentação' ? 'bg-indigo-100 border border-indigo-300' : 'bg-gray-50' }}">
                                        {{-- Mostrar algo si está en esta etapa --}}
                                        {{-- Esto es un placeholder. La lógica real del Kanban vendrá después. --}}
                                        {{ $opportunity->getOpportunityStatus()->getStatus() == 'Apresentação' ? 'Atual' : '-' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 text-center">
                                    <div class="kanban-card p-2 rounded-md {{ $opportunity->getOpportunityStatus()->getStatus() == 'Proposta' ? 'bg-blue-100 border border-blue-300' : 'bg-gray-50' }}">
                                        {{ $opportunity->getOpportunityStatus()->getStatus() == 'Proposta' ? 'Atual' : '-' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 text-center">
                                    <div class="kanban-card p-2 rounded-md {{ $opportunity->getOpportunityStatus()->getStatus() == 'Negociação' ? 'bg-purple-100 border border-purple-300' : 'bg-gray-50' }}">
                                        {{ $opportunity->getOpportunityStatus()->getStatus() == 'Negociação' ? 'Atual' : '-' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-700 text-center">
                                    <div class="kanban-card p-2 rounded-md
                                        @if ($opportunity->getOpportunityStatus()->getStatus() == 'Won')
                                            bg-green-100 border border-green-300
                                        @elseif ($opportunity->getOpportunityStatus()->getStatus() == 'Lost')
                                            bg-red-100 border border-red-300
                                        @else
                                            bg-gray-50
                                        @endif
                                    ">
                                        {{ $opportunity->getOpportunityStatus()->getStatus() == 'Won' ? 'Ganho' : ($opportunity->getOpportunityStatus()->getStatus() == 'Lost' ? 'Perdido' : '-') }}
                                    </div>
                                </td>

                                {{-- Columna Nova Atividade --}}
                                <td class="py-4 px-6 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900"
                                       onclick="alert('Funcionalidade de Nova Atividade para {{ $opportunity->getOpportunityName() }} (ID: {{ $opportunity->getOpportunityId() }}) será implementada mais tarde.'); return false;">
                                        &#x2B; {{-- Unicode para un signo de más --}}
                                    </a>
                                </td>

                                {{-- Columna Documentos --}}
                                <td class="py-4 px-6 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="#" class="text-green-600 hover:text-green-900"
                                       onclick="alert('Funcionalidade de Documentos para {{ $opportunity->getOpportunityName() }} (ID: {{ $opportunity->getOpportunityId() }}) será implementada mais tarde.'); return false;">
                                        &#x1F4C4; {{-- Unicode para un icono de documento --}}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-4 px-6 text-center text-sm text-gray-500">
                                    Você não tem oportunidades ativas no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .kanban-card {
            min-width: 80px; /* Ancho mínimo para las tarjetas */
            display: inline-block; /* Para que ocupen solo el espacio necesario */
            font-size: 0.75rem; /* Texto más pequeño */
            padding: 0.5rem; /* Pequeño padding para el contenido */
        }
    </style>
@endpush