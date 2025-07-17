@extends('layouts.app')

@section('page_title', 'Registrar Nueva Actividad para Oportunidad #' . $opportunity->getOpportunityId())

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Registrar Actividad para Oportunidad: <span class="text-blue-600">{{ $opportunity->getOpportunityName() }}</span></h2>

        {{-- Formulario para crear una nueva actividad --}}
        <form action="{{ route('salesperson.opportunities.activities.store', ['opportunityId' => $opportunity->getOpportunityId()]) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título de la Actividad:</label>
                <input type="text" name="titulo" id="titulo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('titulo') border-red-500 @enderror" value="{{ old('titulo') }}" required>
                @error('titulo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="activity_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Fecha de la Actividad:</label>
                            <span id="activity_date_display" class="ml-2 text-gray-700 font-medium">
                                @if(old('activity_date'))
                                    {{ \Carbon\Carbon::parse(old('activity_date'))->format('d/m/Y') }}
                                @endif
                            </span> 
                <div class="flex items-center">
                    <input type="date" name="activity_date" id="activity_date_picker" class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('activity_date') border-red-500 @enderror" value="{{ old('activity_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    @error('activity_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="duration_min" class="block text-gray-700 text-sm font-bold mb-2">Duración (minutos):</label>
                <input type="number" name="duration_min" id="duration_min" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('duration_min') border-red-500 @enderror" value="{{ old('duration_min') }}" min="1">
                @error('duration_min')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                    @foreach ($activityStatuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="result" class="block text-gray-700 text-sm font-bold mb-2">Resultado:</label>
                <select name="result" id="result" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('result') border-red-500 @enderror">
                    @foreach ($activityResults as $value => $label)
                        <option value="{{ $value }}" {{ old('result') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('result')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="comments" class="block text-gray-700 text-sm font-bold mb-2">Comentarios:</label>
                <textarea name="comments" id="comments" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('comments') border-red-500 @enderror">{{ old('comments') }}</textarea>
                @error('comments')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Guardar Actividad
                </button>
                <a href="{{ route('salesperson.myopportunities') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection


@push('scripts')
<script>

// =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHA dd/mm/yyyy
        // =============================================================
        const activityDatePicker = document.getElementById('activity_date_picker');
        const activityDateDisplay = document.getElementById('activity_date_display');

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
        if (activityDatePicker && activityDateDisplay) {
            activityDatePicker.addEventListener('change', function() {
                const selectedDate = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                activityDateDisplay.textContent = formatDateForDisplay(selectedDate);
            });
        }

        // Si ya hay un valor de `old('expected_closing_date')` al cargar la página,
        // el Blade ya lo habrá formateado inicialmente. Pero si se interactúa con
        // el campo sin un `old` value, el JS lo manejará.
        // Asegurarse de que el span muestre el valor inicial si existe.
        if (activityDatePicker.value && activityDateDisplay.textContent.trim() === '') {
             activityDateDisplay.textContent = formatDateForDisplay(activityDatePicker.value);
        }



</script>


@endpush