<?php

/*
File: dashboard
Author: Leonardo G. Tellez Saucedo
Created on: 1 jul. de 2025 20:48:05
Email: leonardo616@gmail.com
*/

    //ATENCIÓN! DESACTIVAR APP_DEBUG EN .ENV AL SUBIR A PRODUCCION!
    if (config('app.debug')) {
        xdebug_break();

    //SOLO PARA DEBUG:
            // Asegúrate de que $user es tu objeto User autenticado
           $user = Auth::user();

           // Para acceder a la relación usersGroup, usa el getter getUsersGroup()
           $usersGroupObject = null;
           if ($user) {
               $usersGroupObject = $user->getUsersGroup();
               
           }
           // Inicializa la variable
           $debugGroupName = ''; 

           if ($usersGroupObject) { // Ahora verificar si el objeto UsersGroup es válido
               $debugGroupName = $usersGroupObject->getGroupName();
           } else {
               $debugGroupName = 'Grupo no encontrado (relación nula)';
           }
        
    }
?>

@extends('layouts.app')

@section('page_title', 'Dashboard Principal')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Bem-vindo, @auth('web') {{ Auth::guard('web')->user()->getUsername() }} @else Usuario @endauth!
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">

        <?php
        
                if (config('app.debug')) {
                    xdebug_break();                
                }
            // Inicializamos la variable que contendrá el nombre del grupo.
            // Esto es útil para darle un valor por defecto si no se encuentra.
            $groupName = 'Grupo não encontrado';

            // Obtenemos el usuario autenticado para no llamarlo múltiples veces.
            // El @auth de Blade ya nos asegura que hay un usuario si entramos en esta sección.
            $authenticatedUser = Auth::guard('web')->user();

            // Verificamos que el usuario exista (aunque @auth ya lo hace) y que su relación usersGroup no sea nula.
            // ¡Aquí es donde usamos el método getter getUsersGroup() para acceder a la relación privada!
            if ($authenticatedUser && $authenticatedUser->getUsersGroup()) {
                // Si el grupo existe, obtenemos su nombre usando getGroupName().
                $groupName = $authenticatedUser->getUsersGroup()->getGroupName();
                // Puedes poner un xdebug_break() aquí para inspeccionar $groupName
                // xdebug_break();
            }
            // Si $authenticatedUser es null o $authenticatedUser->getUsersGroup() es null,
            // $groupName se mantendrá como 'Grupo não encontrado'.
        ?>

            Tu grupo de usuario es:
            <span class="font-semibold">
                @auth('web')                    
                    {{ $groupName }}
                @else                    
                    Não autenticado
                @endauth
            </span>
        </p>

        <div class="mt-5 border-t border-gray-200 pt-5">
            <h4 class="text-md font-semibold text-gray-800 mb-3">Resumo Rapido</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Tarjeta de ejemplo para Gerentes --}}
                @auth('web')
                    @php
                        $userGroupName = $groupName;
                    @endphp
                    
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
                            <p class="text-2xl mt-2">15</p>
                            <p class="text-sm">vs 12 no mes pasado (+25%)</p>
                        </div>
                    @endif

                    {{-- Tarjeta de ejemplo para Vendedores --}}
                    @if ($userGroupName == 'Vendedores')
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

