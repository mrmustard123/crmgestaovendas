<?php

/*
File: create
Author: Leonardo G. Tellez Saucedo
Created on: 3 jul. de 2025 22:18:11
Email: leonardo616@gmail.com
*/
?>
@extends('layouts.app')

@section('page_title', 'Cadastrar Novo Usuário')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Cadastrar Novo Usuário do Sistema</h2>

            {{-- Mensajes de éxito o error (usaremos en el futuro para feedback) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">¡Errores de Validación!</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf {{-- ¡Importante! Protección CSRF para Laravel --}}

                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nome de Usuário:</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('username') border-red-500 @enderror" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email (Opcional):</label>
                    <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" required>
                    @error('password')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Senha:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-6">
                    <label for="user_group_id" class="block text-gray-700 text-sm font-bold mb-2">Grupo de Usuário:</label>
                    <select id="user_group_id" name="user_group_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_group_id') border-red-500 @enderror" required>
                        <option value="">Selecione um Grupo</option>
                        {{-- Este bucle será rellenado por el controlador --}}
                        @isset($userGroups) {{-- Solo si $userGroups está definida --}}
                            @foreach ($userGroups as $group)
                                @if($group->getGroupName() != 'Vendedores')
                                <option value="{{ $group->getUserGroupId() }}" {{ old('user_group_id') == $group->getUserGroupId() ? 'selected' : '' }}>
                                    {{ $group->getGroupName() }}
                                </option>
                                @endif
                            @endforeach
                        @endisset
                    </select>
                    @error('user_group_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cadastrar Usuário
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection