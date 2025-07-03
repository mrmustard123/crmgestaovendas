<?php

/*
File: login
Author: Leonardo G. Tellez Saucedo
Created on: 1 jul. de 2025 16:39:47
Email: leonardo616@gmail.com
*/
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM 360</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Necesario para Axios/Fetch --}}
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Iniciar Sessão</h2>

        <div id="alert-messages" class="mb-4"></div>

        <form id="loginForm" method="POST" action="">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Usuario:</label>
                <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Ingresar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Previene el envío del formulario por defecto

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const alertMessages = document.getElementById('alert-messages');
            alertMessages.innerHTML = ''; // Limpiar mensajes anteriores

            try {
                const response = await fetch('{{ url('/login-session') }}', {                    
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Incluye CSRF para Laravel
                        //'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();                

                if (response.ok) {
                    // Login exitoso
                    console.log('Login bem-sucedido:', data);                    
                    // Guarda el token (ej. en localStorage)
                    localStorage.setItem('jwt_token', data.access_token);
                    // Redirige al usuario al dashboard o página principal
                    window.location.href = '{{ url('/dashboard') }}'; // Cambia esto a tu ruta de dashboard
                    
                } else {
                    // Error en el login
                    console.error('Error de login:', data);
                    let errorMessage = 'Ocorreu um erro ao efetuar login.';
                    if (data.error) {
                        errorMessage = data.error;
                    } else if (data.message) {
                        errorMessage = data.message;
                    }

                    alertMessages.innerHTML = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Erro!</strong>
                            <span class="block sm:inline">${errorMessage}</span>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Erro de rede inesperado:', error);
                alertMessages.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erro!</strong>
                        <span class="block sm:inline">Não foi possível conectar ao servidor. Tente novamente mais tarde.</span>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>


