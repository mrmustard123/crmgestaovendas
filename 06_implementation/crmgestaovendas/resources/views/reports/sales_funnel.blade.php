<?php

/*
File: sales_funnel
Author: Leonardo G. Tellez Saucedo
Created on: 11 jul. de 2025 23:46:18
Email: leonardo616@gmail.com
*/

?>


@extends('layouts.app')

@section('page_title', 'Relatório de Funil de Vendas')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
            Relatório de Funil de Vendas
        </h3>
        
        <form method="GET" action="{{ url('/reports/sales-funnel') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date_picker" class="block text-sm font-medium text-gray-700">Data Início</label>
                            <span id="start_date_display" class="ml-2 text-gray-700 font-medium">
                                @if(old('start_date'))
                                    {{ \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') }}
                                @endif
                            </span>                    
                    <input type="date" id="start_date_picker" name="start_date" 
                           value="{{ old('start_date') }}" 
                           class="mt-1 block w-40 rounded-md border-gray-300 text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="end_date_picker" class="block text-sm font-medium text-gray-700">Data Fim</label>
                            <span id="end_date_display" class="ml-2 text-gray-700 font-medium">
                                @if(old('end_date'))
                                    {{ \Carbon\Carbon::parse(old('end_date'))->format('d/m/Y') }}
                                @endif
                            </span>                    
                    <input type="date" id="end_date_picker" name="end_date" 
                           value="{{ old('end_date') }}" 
                           class="mt-1 block w-40 rounded-md border-gray-300 text-gray-200  shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Gerar Relatório
            </button>
        </form>

        @if(isset($conversionRates))
            <div class="mt-8">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Taxas de Conversão (Oportunidades Ganhas)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @foreach($conversionRates as $stageName => $rate)
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
                            <p class="font-bold">{{ $stageName }}</p>
                            <p class="text-2xl mt-2">{{ number_format($rate, 2) }}%</p>
                            <p class="text-sm">Taxa de conversão</p>
                        </div>
                    @endforeach
                </div>
                <h4 class="text-md font-semibold text-gray-800 mb-4">Taxas de Perda (Oportunidades Perdidas)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($conversionRates as $stageName => $rate)
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                            <p class="font-bold">{{ $stageName }}</p>
                            <p class="text-2xl mt-2">{{ number_format($rate, 2) }}%</p>
                            <p class="text-sm">Taxa de conversão</p>
                        </div>
                    @endforeach
                </div>                
            </div>
        @endif
    </div>
@endsection


@push('scripts')
<script>
        // =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHA dd/mm/yyyy
        // =============================================================
        const expectedClosingDatePicker = document.getElementById('start_date_picker');
        const expectedClosingDateDisplay = document.getElementById('start_date_display');
        
        const expectedClosingDatePicker1 = document.getElementById('end_date_picker');
        const expectedClosingDateDisplay1 = document.getElementById('end_date_display');
        

        // Función para formatear YYYY-MM-DD a DD/MM/YYYY para la visualización
        function formatDateForDisplay(dateString) {
            if (!dateString) return '';
            // Añadir 'T00:00:00' para evitar problemas de zona horaria al parsear la fecha
            const date = new Date(dateString + 'T00:00:00');
            // Validar si la fecha es válida
            if (isNaN(date.getTime())) {
                return dateString; // Si no es una fecha válida, devuelve la cadena original
            }
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() es base 0
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Listener para el input type="date"
        // Cuando el usuario selecciona una fecha con el calendario, actualizamos el <span>
        if (expectedClosingDatePicker && expectedClosingDateDisplay) {
            expectedClosingDatePicker.addEventListener('change', function() {
                const selectedDate = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                expectedClosingDateDisplay.textContent = formatDateForDisplay(selectedDate);
            });
        }

        // Si ya hay un valor de `old('expected_closing_date')` al cargar la página,
        // el Blade ya lo habrá formateado inicialmente. Pero si se interactúa con
        // el campo sin un `old` value, el JS lo manejará.
        // Asegurarse de que el span muestre el valor inicial si existe.
        if (expectedClosingDatePicker.value && expectedClosingDateDisplay.textContent.trim() === '') {
             expectedClosingDateDisplay.textContent = formatDateForDisplay(expectedClosingDatePicker.value);
        }
        
        
       /*PARA END_DATE*/
        if (expectedClosingDatePicker1 && expectedClosingDateDisplay1) {
            expectedClosingDatePicker1.addEventListener('change', function() {
                const selectedDate = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                expectedClosingDateDisplay1.textContent = formatDateForDisplay(selectedDate);
            });
        }

        if (expectedClosingDatePicker1.value && expectedClosingDateDisplay1.textContent.trim() === '') {
             expectedClosingDateDisplay1.textContent = formatDateForDisplay(expectedClosingDatePicker1.value);
        }
    
  
    
</script>
@endpush

