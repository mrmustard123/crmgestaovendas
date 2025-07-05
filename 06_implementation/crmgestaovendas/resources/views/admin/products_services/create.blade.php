<?php

/*
File: create
Author: Leonardo G. Tellez Saucedo
Created on: 3 jul. de 2025 23:36:32
Email: leonardo616@gmail.com
*/
?>
@extends('layouts.app')

@section('page_title', 'Cadastrar Novo Produto/Serviço')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Cadastrar Novo Produto ou Serviço</h2>

            {{-- Mensajes de éxito o error --}}
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

            <form action="{{ route('admin.products-services.store') }}" method="POST">
                @csrf {{-- ¡Importante! Protección CSRF --}}

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto/Serviço:</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição (Opcional):</label>
                    <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo:</label>
                    <select id="type" name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror">
                        <option value="">Seleccione un tipo</option>
                        <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Produto</option>
                        <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Serviço</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Categoría (Opcional):</label>
                    <input type="text" id="category" name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('category') border-red-500 @enderror" value="{{ old('category') }}">
                    @error('category')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Preço Unitário (Opcional):</label>
                    {{-- CAMBIO CLAVE AQUÍ: type="number" a type="text" --}}
                    <input type="text" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" value="{{ old('price') }}">
                    @error('price')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="unit" class="block text-gray-700 text-sm font-bold mb-2">Unidad (Opcional):</label>
                    <input type="text" id="unit" name="unit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit') border-red-500 @enderror" value="{{ old('unit') }}">
                    @error('unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tax_rate" class="block text-gray-700 text-sm font-bold mb-2">Taxa de Imposto (Opcional):</label>
                    {{-- CAMBIO CLAVE AQUÍ: type="number" a type="text" --}}
                    <input type="text" id="tax_rate" name="tax_rate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tax_rate') border-red-500 @enderror" value="{{ old('tax_rate') }}">
                    @error('tax_rate')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sku" class="block text-gray-700 text-sm font-bold mb-2">SKU (Opcional):</label>
                    <input type="text" id="sku" name="sku" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('sku') border-red-500 @enderror" value="{{ old('sku') }}">
                    @error('sku')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Ativo</span>
                        </label>
                        @error('is_active')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Propriedades:</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_tangible" class="form-checkbox h-5 w-5 text-blue-600" value="1" {{ old('is_tangible', 1) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Tangível (físico)</span>
                        </label>
                        @error('is_tangible')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cadastrar Produto/Serviço
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('price');
        const taxRateInput = document.getElementById('tax_rate');
        console.log("javascript funcionando");
        // Función para formatear el número para la visualización (añadir coma y punto de miles)
        // No usaremos esto directamente para la entrada, pero es útil para cuando se cargue un valor.
        function formatNumberForDisplay(value) {
            if (value === null || value === undefined || value === '') {
                return '';
            }
            // Asegurarse de que es un número
            const num = parseFloat(value);
            if (isNaN(num)) {
                return '';
            }
            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Función para limpiar y transformar la entrada del usuario a un formato numérico con punto decimal
        function cleanAndFormatNumberForBackend(value) {
            if (value === null || value === undefined || value === '') {
                return '';
            }
            // 1. Eliminar puntos de miles
            let cleanedValue = String(value).replace(/\./g, '');
            // 2. Reemplazar coma decimal por punto decimal
            cleanedValue = cleanedValue.replace(/,/g, '.');
            // 3. Convertir a float
            return parseFloat(cleanedValue);
        }

        // Event listener para formatear mientras el usuario escribe o al salir del campo
        function applyCurrencyMask(event) {
            let value = event.target.value;

            // Permite solo dígitos, comas y puntos
            value = value.replace(/[^0-9,\.]/g, '');

            // Si ya hay una coma, evita otra
            if (value.indexOf(',') !== -1) {
                const parts = value.split(',');
                value = parts[0] + ',' + parts[1].slice(0, 2); // Limita a 2 decimales después de la coma
            }

            // Para la visualización, no necesitamos formatear en tiempo real con miles si el usuario está escribiendo.
            // Simplemente asegúrate de que solo haya una coma decimal y 2 decimales.
            event.target.value = value;
        }

        // Aplicar los listeners
        if (priceInput) {
            priceInput.addEventListener('input', applyCurrencyMask);
            // Cuando se carga la página o hay un valor old, formatearlo para la vista
            if (priceInput.value) {
                // old('price') ya viene en formato de punto decimal (del backend)
                // lo formateamos para que se vea con coma decimal al cargar la página
                priceInput.value = formatNumberForDisplay(priceInput.value);
            }
        }

        if (taxRateInput) {
            taxRateInput.addEventListener('input', applyCurrencyMask);
            if (taxRateInput.value) {
                 taxRateInput.value = formatNumberForDisplay(taxRateInput.value);
            }
        }

        // Manejar el envío del formulario: transformar los valores a formato de punto antes de enviar
        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            if (priceInput) {
                // Reemplaza el valor del campo con el formato de punto para el backend
                priceInput.value = cleanAndFormatNumberForBackend(priceInput.value);
            }
            if (taxRateInput) {
                // Reemplaza el valor del campo con el formato de punto para el backend
                taxRateInput.value = cleanAndFormatNumberForBackend(taxRateInput.value);
            }
        });
    });
</script>
@endpush
