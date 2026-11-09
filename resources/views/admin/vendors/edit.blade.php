<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
?>
@extends('layouts.app')

@section('page_title', 'Editar Vendedor')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Editar Vendedor e Usuário</h2>

            {{-- Mensajes de éxito o error --}}
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

            @if (session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">¡Errores de Validação!</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulário de Edição --}}
            <form action="{{ route('reports.vendors.update', $vendor->getVendorId()) }}" method="POST">
                @csrf
                @method('PUT') {{-- Usar método PUT para atualização --}}

                {{-- Campo oculto para o user_id, necessário para a validação unique no update --}}
                @if ($user)
                    <input type="hidden" name="user_id" value="{{ $user->getUserId() }}">
                @endif

                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Dados do Usuário</h3>

                @if ($user)
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nome de Usuário:</label>
                        <input type="text" id="username" name="username"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('username') border-red-500 @enderror"
                            value="{{ old('username', $user->getUsername()) }}" required>
                        @error('username')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Principal (Opcional):</label>
                        <input type="email" id="email" name="email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                            value="{{ old('email', $user->getEmail()) }}">
                        @error('email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nova Senha (deixe em branco para não alterar):</label>
                        <input type="password" id="password" name="password"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirme a Nova Senha:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Checkbox "Restrito" --}}
                    <div class="mb-6 flex items-center">
                        <input type="checkbox" id="restricted" name="restricted" value="1"
                            class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                            {{ old('restricted', ($user->getUsersGroup() && $restrictedGroup && $user->getUsersGroup()->getUserGroupId() === $restrictedGroup->getUserGroupId())) ? 'checked' : '' }}>
                        <label for="restricted" class="ml-2 block text-gray-900 text-sm font-bold">
                            Restrito (Mover para o grupo "Restrito")
                        </label>
                    </div>
                @else
                    <p class="text-red-500 text-sm italic mb-6">Nenhum usuário associado a este vendedor.</p>
                @endif

                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Dados do Vendedor</h3>

                <div class="mb-4">
                    <label for="vendor_name" class="block text-gray-700 text-sm font-bold mb-2">Nome Completo do Vendedor:</label>
                    <input type="text" id="vendor_name" name="vendor_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('vendor_name') border-red-500 @enderror"
                        value="{{ old('vendor_name', $vendor->getVendorName()) }}" required>
                    @error('vendor_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="main_phone" class="block text-gray-700 text-sm font-bold mb-2">Telefone Principal (Opcional):</label>
                        <input type="text" id="main_phone" name="main_phone"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('main_phone') border-red-500 @enderror"
                            value="{{ old('main_phone', $vendor->getMainPhone()) }}">
                        @error('main_phone')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="main_email" class="block text-gray-700 text-sm font-bold mb-2">Email Principal (Opcional):</label>
                        <input type="email" id="main_email" name="main_email"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('main_email') border-red-500 @enderror"
                            value="{{ old('main_email', $vendor->getMainEmail()) }}">
                        @error('main_email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Endereço (Opcional):</label>
                    <input type="text" id="address" name="address"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror"
                        value="{{ old('address', $vendor->getAddress()) }}">
                    @error('address')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="complement" class="block text-gray-700 text-sm font-bold mb-2">Complemento (Opcional):</label>
                    <input type="text" id="complement" name="complement"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('complement') border-red-500 @enderror"
                        value="{{ old('complement', $vendor->getComplement()) }}">
                    @error('complement')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="neighborhood" class="block text-gray-700 text-sm font-bold mb-2">Bairro (Opcional):</label>
                        <input type="text" id="neighborhood" name="neighborhood"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('neighborhood') border-red-500 @enderror"
                            value="{{ old('neighborhood', $vendor->getNeighborhood()) }}">
                        @error('neighborhood')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Cidade (Opcional):</label>
                        <input type="text" id="city" name="city"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror"
                            value="{{ old('city', $vendor->getCity()) }}">
                        @error('city')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="state" class="block text-gray-700 text-sm font-bold mb-2">Estado (UF, Opcional):</label>
                        <input type="text" id="state" name="state"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('state') border-red-500 @enderror"
                            value="{{ old('state', $vendor->getState()) }}">
                        @error('state')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="country" class="block text-gray-700 text-sm font-bold mb-2">País:</label>
                        <input type="text" id="country" name="country"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('country') border-red-500 @enderror"
                            value="{{ old('country', $vendor->getCountry()) }}" required>
                        @error('country')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Atualizar Vendedor
                    </button>
                    <a href="{{ route('reports.vendor_performance') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
