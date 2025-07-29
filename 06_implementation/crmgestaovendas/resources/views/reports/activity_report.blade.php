<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
?>
@extends('layouts.app')

@section('page_title', 'Relatório de Atividades')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Relatório de Atividades</h2>

            {{-- Mensajes de error de validación --}}
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

            {{-- Formulário de Filtro de Datas --}}
            <form action="{{ route('reports.activity_report') }}" method="GET" class="mb-8 p-4 border rounded-lg bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Data Inicial:</label>
                        <span id="start_date_display" class="ml-2 text-gray-700 font-medium">
                             @if(old('start_date'))
                                 {{ \Carbon\Carbon::parse(old('start_date'))->format('d/m/Y') }}
                             @endif
                         </span>    
                        <div class="flex items-center">
                            <input type="date" id="start_date_picker" name="start_date"
                                class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                                value="{{ old('start_date', $startDate ? $startDate->format('Y-m-d') : '') }}">
                            @error('start_date')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="end_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Data Final:</label>
                        <span id="end_date_display" class="ml-2 text-gray-700 font-medium">
                             @if(old('end_date'))
                                 {{ \Carbon\Carbon::parse(old('end_date'))->format('d/m/Y') }}
                             @endif
                         </span> 
                        <div class="flex items-center">
                            <input type="date" id="end_date_picker" name="end_date"
                                class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror"
                                value="{{ old('end_date', $endDate ? $endDate->format('Y-m-d') : '') }}">
                            @error('end_date')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Gerar Relatório
                    </button>
                </div>
            </form>

            {{-- Tabela de Resultados do Relatório --}}
            @if (!empty($reportData))
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo de Atividade
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantidade
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($reportData as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $data['activity_type'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $data['count'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-600 text-lg">
                    Por favor, selecione um período para gerar o relatório de atividades.
                </p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
       // =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHAS dd/mm/yyyy
        // =============================================================
        const startDatePicker = document.getElementById('start_date_picker');
        const startDateDisplay = document.getElementById('start_date_display');

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
        if (startDatePicker && startDateDisplay) {
            startDatePicker.addEventListener('change', function() {
                const selectedDate = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                startDateDisplay.textContent = formatDateForDisplay(selectedDate);
            });
        }

        // Si ya hay un valor de `old('expected_closing_date')` al cargar la página,
        // el Blade ya lo habrá formateado inicialmente. Pero si se interactúa con
        // el campo sin un `old` value, el JS lo manejará.
        // Asegurarse de que el span muestre el valor inicial si existe.
        if (startDatePicker.value && staratDisplay.textContent.trim() === '') {
             startDateDisplay.textContent = formatDateForDisplay(startDatePicker.value);
        }
        
        
        const endDatePicker = document.getElementById('end_date_picker');
        const endDateDisplay = document.getElementById('end_date_display');
        
        if (endDatePicker && endDateDisplay) {
            endDatePicker.addEventListener('change', function() {
                const selectedDate1 = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                endDateDisplay.textContent = formatDateForDisplay(selectedDate1);
            });
        }    
        
        if (endDatePicker.value && endDateDisplay.textContent.trim() === '') {
             endDateDisplay.textContent = formatDateForDisplay(endDatePicker.value);
        }   



</script>


@endpush
