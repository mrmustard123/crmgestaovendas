<?php

/*
File: app
Author: Leonardo G. Tellez Saucedo
Created on: 1 jul. de 2025 20:46:05
Email: leonardo616@gmail.com
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM 360</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

    @stack('styles') {{-- Para CSS específico de la página --}}
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div id="app" class="flex h-screen">

        {{-- Menú Lateral Izquierdo --}}
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 border-b border-gray-700">
                <h1 class="text-2xl font-semibold">CRM 360</h1>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2">
                {{-- Aquí irán los ítems de menú dinámicos según el rol del usuario --}}

                @auth('api') {{-- Usamos el guard 'api' si tu usuario autenticado proviene del JWT --}}
                    <?php
                        // Recuperar el usuario autenticado (si está disponible, ej. de una sesión o de la verificación de JWT para Blade)
                        // Para este ejemplo, simplificamos asumiendo que el usuario ya está cargado por el middleware 'auth.jwt'
                        // Obtiene el usuario de JWTAuth
                        $currentUser = Auth::guard('api')->user();
                        $userGroupName = $currentUser->usersGroup ? $currentUser->usersGroup->getGroupName() : 'Desconhecido';
                    ?>

                    {{-- Menú para Administradores --}}
                    @if ($userGroupName == 'Administrador')
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M12 20.354a4 4 0 110-5.292M12 14.354a4 4 0 110-5.292M12 4.354a4 4 0 100 5.292M12 20.354a4 4 0 100-5.292M12 14.354a4 4 0 100-5.292"></path></svg>
                            <span>Registrar Vendedores</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Criar nome de usuário/senha</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2m2-3h5m0 0a3 3 0 00-5.356-1.857M7 20v-2a3 3 0 015.356-1.857M7 20H2m2-3h5m0 0a3 3 0 00-5.356-1.857M7 20v-2a3 3 0 015.356-1.857"></path></svg>
                            <span>Designar a grupos</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Designar Funcionalidades</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Registrar Produtos/Serviços</span>
                        </a>
                    @endif

                    {{-- Menú para Gerentes --}}
                    @if ($userGroupName == 'Gerente')
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span>Relatório de Conversão</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            <span>Relatório Forecast</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2m0-8a6 6 0 00-6 6v1a3 3 0 003 3h4a3 3 0 003-3v-1a6 6 0 00-6-6zM2 12c.732 0 1.41.22 2 .607V14a2 2 0 002 2h8a2 2 0 002-2v-1.393c.59-.387 1.268-.607 2-.607 1.657 0 3 .895 3 2s-1.343 2-3 2H5c-1.657 0-3-.895-3-2s1.343-2 3-2z"></path></svg>
                            <span>Relatório Desempenho</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M18 19H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>
                            <span>Relatório Atividades</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Relatório Origem de Leads</span>
                        </a>
                    @endif

                    {{-- Menú para Vendedores --}}
                    @if ($userGroupName == 'Vendedor')
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>Registrar Lead / Empresa</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.592 1L21 12h-3.828c-.472 1.173-1.996 2-3.172 2s-2.7-1.077-3.172-2H3l.008-.008A6.002 6.002 0 0112 8z"></path></svg>
                            <span>Registrar Oportunidade</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M7 16h.01M13 16h.01M17 16h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span>Registrar Atividades</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            <span>Mover Oportunidade / Fechar</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5A2.5 2.5 0 005 7.5c0 1.357.6 2.565 1.5 3.314m0 0l.008.008zm0 0l-.008.008zm0 0l-.008.008zM12 6.253c1.168.807 2.754 1.253 4.5 1.253 1.746 0 3.332-.446 4.5-1.253m-4.5 0v13m-4.5 0c-1.168-.807-2.754-1.253-4.5-1.253-1.746 0-3.332.446-4.5 1.253"></path></svg>
                            <span>Vincular Produtos/Serviços</span>
                        </a>
                    @endif
                @else
                    {{-- Si no hay usuario autenticado o el guard 'api' no tiene un usuario --}}
                    <p class="text-gray-400 px-4 py-2 text-sm">Carregando menú...</p>
                @endauth
            </nav>

            <div class="p-4 border-t border-gray-700 mt-auto">
                <p class="text-gray-400 text-sm">© {{ date('Y') }} CRM 360</p>
            </div>
        </aside>

        {{-- Contenido Principal --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Encabezado Superior --}}
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center">
                    {{-- Aquí puedes poner un título dinámico de la página --}}
                    <h2 class="text-xl font-semibold text-gray-800">
                        @yield('page_title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center space-x-4">
                    {{-- Búsqueda Global (opcional por ahora) --}}
                    {{-- <input type="text" placeholder="Buscar..." class="px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"> --}}

                    {{-- Notificaciones (opcional por ahora) --}}
                    {{-- <button class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button> --}}

                    {{-- Menú de Usuario --}}
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span class="font-medium">
                                @auth('api')
                                    {{ Auth::guard('api')->user()->getUsername() }}
                                @else
                                    Invitado
                                @endauth
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="userMenuDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Configuración</a>
                            <div class="border-t border-gray-100"></div>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fechar Sessão</a>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Contenido Principal (donde las vistas específicas inyectarán su contenido) --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Lógica para mostrar/ocultar el menú de usuario
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        if (userMenuButton) {
            userMenuButton.addEventListener('click', function() {
                userMenuDropdown.classList.toggle('hidden');
            });

            // Cerrar el menú si se hace clic fuera
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }


        // Lógica para el botón de cerrar sesión
        const logoutButton = document.getElementById('logoutButton');
        if (logoutButton) {
            logoutButton.addEventListener('click', async function(event) {
                event.preventDefault(); // Previene el comportamiento por defecto del enlace

                const token = localStorage.getItem('jwt_token');

                if (!token) {
                    // Si no hay token, simplemente redirige al login
                    window.location.href = '/login';
                    return;
                }

                try {
                    const response = await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token, // Envía el token en el header
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        console.log('Sesión cerrada exitosamente');
                    } else {
                        console.error('Erro ao fechar sessão:', await response.json());
                    }
                } catch (error) {
                    console.error('Erro de rede ao fechar sessão:', error);
                } finally {
                    localStorage.removeItem('jwt_token'); // Siempre elimina el token
                    window.location.href = '/login'; // Redirige al login
                }
            });
        }

        // Script para cargar dinámicamente el contenido de los links del menú
        // Por ahora, solo tenemos links '#' (vacíos). Cuando tengamos rutas reales,
        // esto cambiará para cargar contenido sin recargar la página (usando fetch y actualizando @yield('content'))
        // o para redirigir a URLs Blade normales.
        // Para empezar, los links del menú simplemente serán rutas de Blade.

    </script>

    @stack('scripts') {{-- Para JS específico de la página --}}
</body>
</html>


