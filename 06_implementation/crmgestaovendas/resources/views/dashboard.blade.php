<?php

/*
File: dashboard
Author: Leonardo G. Tellez Saucedo
Created on: 1 jul. de 2025 20:48:05
Email: leonardo616@gmail.com
*/

?>

@extends('layouts.app')

@section('page_title', 'Dashboard Principal')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Bem-vindo, @auth('web') {{ Auth::guard('web')->user()->getUsername() }} @else Usuário @endauth!
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">

            
           
            

            O seu grupo de usuário é:
            <span class="font-semibold">
                @auth('web')
                    {{ $userGroupName }}
                @else
                    Não autenticado
                @endauth
            </span>
        </p>

        <div class="mt-5 border-t border-gray-200 pt-5">
            <h4 class="text-md font-semibold text-gray-800 mb-3">Resumo Rápido</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Tarjeta de ejemplo para Gerentes --}}
                @auth('web')

                    @if ($userGroupName == 'Administradores')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                            <p class="font-bold">Configurações do sistema</p>
                            <p class="text-sm">CONFIGURAÇÕES</p>

                            {{-- Aquí irán los enlaces --}}
                            <ul class="mt-4 list-disc list-inside">
                                <li><a href="{{ url('/admin/users/create') }}" class="text-green-800 hover:underline">Cadastrar usuário do sistema</a></li>
                                <li><a href="{{ url('/admin/products-services/create') }}" class="text-green-800 hover:underline">Cadastrar Produtos-Serviços</a></li>
                                {{-- Podríamos añadir un enlace para 'Asignar funcionalidades a grupos de usuarios' más adelante si es necesario --}}
                            </ul>

                        </div>
                    @endif

                    @if ($userGroupName == 'Gerentes')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                            <p class="font-bold">Oportunidades conquistadas neste mês</p>
                            <p class="text-2xl mt-2">{{ $dashboardData['opportunitiesWonCurrentMonth'] ?? 0 }}</p>
                            <p class="text-sm">vs {{ $dashboardData['opportunitiesWonLastMonth'] ?? 0 }} no mês passado
                                @if (isset($dashboardData['percentageChange']))
                                    ({{ $dashboardData['percentageChange'] >= 0 ? '+' : '' }}{{ $dashboardData['percentageChange'] }}%)
                                @endif
                            </p>
                            <a href="{{ route('reports.sales-funnel') }}" class="mt-2 inline-block text-sm text-green-800 hover:underline">
                                Ver Relatório de Funil de Vendas
                            </a>
                        </div>
                    @endif

                    {{-- Tarjeta para Vendedores --}}
                    @if ($userGroupName == 'Vendedores')
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                            <p class="font-bold">Leads Registrados hoje</p>
                            <p class="text-2xl mt-2">{{ $dashboardData['leadsTodayCount'] ?? 0 }}</p>
                            <p class="text-sm">da sua meta diária de {{ $dashboardData['dailyLeadGoal'] ?? 0 }}</p>
                        </div>

                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
                            <p class="font-bold">Atividades pendentes</p>
                            <p class="text-2xl mt-2">{{ $dashboardData['pendingActivitiesCount'] ?? 0 }}</p>
                            <p class="text-sm">Verifique a sua agenda</p>
                        </div>

                        <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 rounded shadow">
                            <p class="font-bold">Oportunidades em andamento</p>
                            <p class="text-2xl mt-2">{{ $dashboardData['opportunitiesInProgressCount'] ?? 0 }}</p>
                            <p class="text-sm">Valor estimado: US$ {{ number_format($dashboardData['estimatedValueInProgress'] ?? 0, 2, ',', '.') }}</p>
                        </div>
                    
                    
                    @endif

                @else
                    <p class="text-gray-600">Faça login para visualizar seu dashboard.</p>
                @endauth
            </div>
        </div>
    </div>
@endsection

