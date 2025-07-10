<?php

/*
File: app
Author: Leonardo G. Tellez Saucedo
Created on: 1 jul. de 2025 20:46:05
Email: leonardo616@gmail.com
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'CRM 360')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 text-xl font-bold border-b border-gray-700">
                CRM 360
            </div>
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/dashboard') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Dashboard</a>
                    </li>

                    {{-- Lógica para mostrar el menú basado en el grupo del usuario --}}
                    @auth('web')
                        @php
                            // Obtenemos el usuario autenticado
                            $authenticatedUser = Auth::guard('web')->user();
                            $userGroupName = '';

                            // Verificamos si el usuario y su grupo existen
                            if ($authenticatedUser && $authenticatedUser->getUsersGroup()) {
                                $userGroupName = $authenticatedUser->getUsersGroup()->getGroupName();
                            }
                        @endphp

                        {{-- Menú para Administradores --}}
                        @if ($userGroupName == 'Administradores')
                            <li class="mt-4 pt-4 border-t border-gray-700">
                                <span class="block py-2 px-4 text-gray-400 font-semibold">Administração</span>
                            </li>
                            <li>
                                <a href="{{ url('/admin/users/create') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Cadastrar Usuário</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/products-services/create') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Cadastrar Produtos/Serviços</a>
                            </li>
                            <li>
                                <a href="{{ url('/admin/vendors/create') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Cadastrar Vendedores</a>
                            </li>                            
                            {{-- Aquí podrías añadir más ítems de menú para administradores --}}
                        @endif

                        {{-- Menú para Gerentes (ejemplo) --}}
                        @if ($userGroupName == 'Gerentes')
                            <li class="mt-4 pt-4 border-t border-gray-700">
                                <span class="block py-2 px-4 text-gray-400 font-semibold">Gerência</span>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Ver Relatórios</a>
                            </li>
                            {{-- Más ítems para gerentes --}}
                        @endif

                        {{-- Menú para Vendedores (ejemplo) --}}
                        @if ($userGroupName == 'Vendedores')
                            <li class="mt-4 pt-4 border-t border-gray-700">
                                <span class="block py-2 px-4 text-gray-400 font-semibold">Vendas</span>
                            </li>
                            <li>
                                <a href="#" class="block py-2 px-4 rounded hover:bg-gray-700">Meus Leads</a>
                            </li>
                            <li>
                                <a href="{{ url('/salesperson/leads/create') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Cadastrar Lead</a>
                            </li> 
                            <li>
                                <a href="{{ url('/salesperson/myopportunities') }}" class="block py-2 px-4 rounded hover:bg-gray-700">Minhas oportunidades</a>
                            </li>                             
                        @endif

                    @endauth
                </ul>
            </nav>
            <div class="p-4 border-t border-gray-700">
                @auth('web')
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded block text-center">
                        Login
                    </a>
                @endauth
            </div>
        </aside>

        <main class="flex-1 p-6 overflow-y-auto">
            <header class="bg-white shadow p-4 mb-6 rounded-lg flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Página')</h1>
                <div class="text-gray-600">
                    @auth('web')
                        Bem-vindo, <span class="font-semibold">{{ Auth::guard('web')->user()->getUsername() }}</span>!
                        <span class="font-semibold">
                            @php
                                $groupName = 'Grupo não encontrado';
                                $authenticatedUser = Auth::guard('web')->user();
                                if ($authenticatedUser && $authenticatedUser->getUsersGroup()) {
                                    $groupName = $authenticatedUser->getUsersGroup()->getGroupName();
                                }
                            @endphp
                            ({{ $groupName }})
                        </span>
                    @else
                        Visitante
                    @endauth
                </div>
            </header>

            @yield('content')
        </main>
    </div>

    <script>
        // Puedes añadir aquí lógica JavaScript para el menú si necesitas interactividad (ej. toggles)
        // Por ahora, solo es HTML/CSS
    </script>
    @stack('scripts')
</body>
</html>