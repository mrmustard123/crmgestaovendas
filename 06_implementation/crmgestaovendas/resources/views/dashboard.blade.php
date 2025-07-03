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


<?php
    //solo para debug
   $debug=Auth::guard('api')->user()->getUsername();
   $debug='';                       
?>

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Bem-vindo, @auth('api') {{ Auth::guard('api')->user()->getUsername() }} @else Usuario @endauth!
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Tu grupo de usuario es:
            <span class="font-semibold">
<?php
    //solo para debug
    $debug=Auth::guard('api')->user()->usersGroup ? Auth::guard('api')->user()->usersGroup->getGroupName() : 'Grupo não encontrado';
    $debug=$debug.'';
?>
                @auth('api')
                    {{ Auth::guard('api')->user()->usersGroup ? Auth::guard('api')->user()->usersGroup->getGroupName() : 'Grupo não encontrado' }}
                @else
                    Não autenticado
                @endauth
            </span>
        </p>

        <div class="mt-5 border-t border-gray-200 pt-5">
            <h4 class="text-md font-semibold text-gray-800 mb-3">Resumo Rapido</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Tarjeta de ejemplo para Gerentes --}}
                @auth('api')
                    <?php
                        $userGroupName = Auth::guard('api')->user()->usersGroup ? Auth::guard('api')->user()->usersGroup->getGroupName() : '';
                    ?>

                    @if ($userGroupName == 'Gerente')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                            <p class="font-bold">Oportunidades conquistadas neste mês</p>
                            <p class="text-2xl mt-2">15</p>
                            <p class="text-sm">vs 12 no mes pasado (+25%)</p>
                        </div>
                    @endif

                    {{-- Tarjeta de ejemplo para Vendedores --}}
                    @if ($userGroupName == 'Vendedor')
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                            <p class="font-bold">Leads Registrados hoje</p>
                            <p class="text-2xl mt-2">5</p>
                            <p class="text-sm">da sua meta diária de 10</p>
                        </div>
                    @endif

                    {{-- Tarjeta de ejemplo genérica --}}
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
                        <p class="font-bold">Atividades pendentes</p>
                        <p class="text-2xl mt-2">7</p>
                        <p class="text-sm">Verifique a sua agenda</p>
                    </div>

                    <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 rounded shadow">
                        <p class="font-bold">Oportunidades em andamento</p>
                        <p class="text-2xl mt-2">23</p>
                        <p class="text-sm">Valor estimado: US$ 150.000</p>
                    </div>

                @else
                    <p class="text-gray-600">Faça login para visualizar seu dashboard.</p>
                @endauth
            </div>
        </div>
    </div>
@endsection

