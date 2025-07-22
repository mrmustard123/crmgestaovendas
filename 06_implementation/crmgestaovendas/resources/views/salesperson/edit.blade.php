@extends('layouts.app')

@section('page_title', 'Editar Oportunidad: ' . $opportunity->getOpportunityName())

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Oportunidade: <span class="text-blue-600">{{ $opportunity->getOpportunityName() }}</span></h2>

        {{-- Mensajes de sesión (si existen) --}}
        @if (session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Erro!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mb-8">
            {{-- Formulario de Edición de Oportunidad (TU FORMULARIO EXISTENTE) --}}
            <form action="{{ route('salesperson.opportunities.update', ['id' => $opportunity->getOpportunityId()]) }}" method="POST">
                @csrf
                @method('PUT') {{-- O PATCH, según tu preferencia para actualizaciones --}}

                {{-- Campo Oportunity Name --}}
                <div class="mb-4">
                    <label for="opportunity_name" class="block text-gray-700 text-sm font-bold mb-2">Nome da oportunidade:</label>
                    <input type="text" name="opportunity_name" id="opportunity_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('opportunity_name') border-red-500 @enderror" value="{{ old('opportunity_name', $opportunity->getOpportunityName()) }}" required>
                    @error('opportunity_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Description --}}
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição:</label>
                    <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $opportunity->getDescription()) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Estimated Sale --}}
                <div class="mb-4">
                    <label for="estimated_sale" class="block text-gray-700 text-sm font-bold mb-2">Venta Estimada:</label>
                    <input type="text" name="estimated_sale" id="estimated_sale" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estimated_sale') border-red-500 @enderror" value="{{ old('estimated_sale', $opportunity->getEstimatedSale()) }}" required>
                    @error('estimated_sale')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Campo Final Price --}}
                <div class="mb-4">
                    <label for="final_price" class="block text-gray-700 text-sm font-bold mb-2">Preço de Fechamento:</label>
                    <input type="text" name="final_price" id="estimated_sale" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('final_price') border-red-500 @enderror" value="{{ old('final_price', $opportunity->getFinalPrice()) }}" >
                    @error('final_price')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>                

                {{-- Campo Expected Closing Date --}}
                <div class="mb-4">
                    <label for="expected_closing_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Data de Fechamento Prevista:</label>
                    <span id="expected_closing_date_display" class="ml-2 text-gray-700 font-medium">
                        @if(old('expected_closing_date'))
                            {{ \Carbon\Carbon::parse(old('expected_closing_date'))->format('d/m/Y') }}
                        @endif
                    </span> 
                    <div class="flex items-center">
                        <input type="date" name="expected_closing_date" id="expected_closing_date_picker" class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('expected_closing_date') border-red-500 @enderror" value="{{ old('expected_closing_date', $opportunity->getExpectedClosingDate() ? $opportunity->getExpectedClosingDate()->format('Y-m-d') : '') }}">
                        @error('expected_closing_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                    </div>
                </div>


                {{-- Campo Currency --}}
                <div class="mb-4">
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Moeda:</label>
                    <input type="text" name="currency" id="currency" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('currency') border-red-500 @enderror" value="{{ old('currency', $opportunity->getCurrency()) }}" required>
                    @error('currency')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Lead Origin --}}
                <div class="mb-4">
                    <label for="lead_origin_id" class="block text-gray-700 text-sm font-bold mb-2">Origem do Lead:</label>
                    <select name="lead_origin_id" id="lead_origin_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lead_origin_id') border-red-500 @enderror">
                        <option value="">Selecione uma Origem</option>
                        @foreach ($leadOrigins as $origin)
                            <option value="{{ $origin->getLeadOriginId() }}" {{ old('lead_origin_id', $opportunity->getLeadOrigin() ? $opportunity->getLeadOrigin()->getLeadOriginId() : '') == $origin->getLeadOriginId() ? 'selected' : '' }}>
                                {{ $origin->getOrigin() }}
                            </option>
                        @endforeach
                    </select>
                    @error('lead_origin_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Opportunity Status --}}
                <div class="mb-4">
                    <label for="fk_op_status_id" class="block text-gray-700 text-sm font-bold mb-2">Estado da Oportunidade:</label>
                    <select name="fk_op_status_id" id="fk_op_status_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fk_op_status_id') border-red-500 @enderror" required>
                        <option value="">Selecione um estado</option>
                        @foreach ($opportunityStatuses as $status)
                            <option value="{{ $status->getOpportunityStatusId() }}" {{ old('fk_op_status_id', $opportunity->getOpportunityStatus()->getOpportunityStatusId()) == $status->getOpportunityStatusId() ? 'selected' : '' }}>
                                {{ $status->getStatus() }}
                            </option>
                        @endforeach
                    </select>
                    @error('fk_op_status_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Priority --}}
                <div class="mb-4">
                    <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Prioridade:</label>
                    <select name="priority" id="priority" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('priority') border-red-500 @enderror" required>
                        @foreach (['Low', 'Medium', 'High', 'Critical'] as $p)
                            <option value="{{ $p }}" {{ old('priority', $opportunity->getPriority()) == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Campo Open Date --}}
                <div class="mb-4">
                    <label for="open_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Data de abertura:</label>
                    <span id="open_date_display" class="ml-2 text-gray-700 font-medium">
                         @if(old('expected_closing_date'))
                             {{ \Carbon\Carbon::parse(old('expected_closing_date'))->format('d/m/Y') }}
                         @endif
                     </span>
                    <div class="flex items-center">
                        <input type="date" name="open_date" id="open_date_picker" class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('open_date') border-red-500 @enderror" value="{{ old('open_date', $opportunity->getOpenDate() ? $opportunity->getOpenDate()->format('Y-m-d') : '') }}" required>
                        @error('open_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror                                                
                    </div>
                </div>

                {{-- Lista de Productos/Servicios Asociados (EXISTENTE) --}}
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Produtos/Serviços Associados:</label>
                    @if ($opportunity->getProdServOpps()->isEmpty())
                        <p class="text-gray-600">No hay productos o servicios asociados a esta oportunidad.</p>
                    @else
                        <ul class="list-disc pl-5">
                            @foreach ($opportunity->getProdServOpps() as $prodServOpp)
                                @php
                                    $productService = $prodServOpp->getProductService(); // Accede al ProductService a través de ProdServOpp
                                @endphp
                                <li class="mb-2 text-gray-700">
                                    <span class="font-semibold">{{ $productService->getName() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>                


                {{-- Botones de acción --}}
                <div class="flex items-center justify-between mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Atualizar Oportunidade
                    </button>
                    <a href="{{ url()->previous() }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Sección de Actividades Relacionadas --}}
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Actividades Relacionadas</h3>
            @if ($opportunity->getActivities()->isEmpty())
                <p class="text-gray-600">Não há atividades registradas para esta oportunidade..</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Data</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Título</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($opportunity->getActivities() as $activity)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 text-gray-700">{{ $activity->getActivityDate()->format('d/m/Y') }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $activity->getTitulo() }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $activity->getDescription() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="mt-4 text-right">
                {{-- Enlace para añadir nueva actividad, asumiendo la ruta ya existe --}}
                <a href="{{ route('salesperson.opportunities.activities.create', ['opportunityId' => $opportunity->getOpportunityId()]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    &#x270F;&#xFE0F; Añadir Nueva Actividad
                </a>
            </div>
        </div>

        {{-- Sección de Documentos Asociados --}}
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Documentos Associados</h3>
            @if ($opportunity->getDocuments()->isEmpty())
                <p class="text-gray-600">Não há documentos associados a esta oportunidade.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Nome do Documento</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Descrição</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Tipo</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Tamanho</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Data de Upload</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($opportunity->getDocuments() as $document)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 text-gray-700">
                                        {{-- Link de descarga. Usa Storage::url() para obtener la URL pública --}}
                                        <a href="{{ Storage::url($document->getFilePath()) }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ $document->getFileName() }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ $document->getDescription() ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $document->getMimeType() ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $document->getFileSize() ? round($document->getFileSize() / 1024, 2) . ' KB' : 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $document->getUploadedAt() ? $document->getUploadedAt()->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td class="py-3 px-4">
                                        <a href="{{ Storage::url($document->getFilePath()) }}" target="_blank" download="{{ $document->getFileName() }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            Descargar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="mt-4 text-right">
                {{-- Enlace para subir nuevo documento, asumiendo la ruta ya existe --}}
                <a href="{{ route('salesperson.opportunities.documents.upload', ['opportunityId' => $opportunity->getOpportunityId()]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    &#x1F4C4; Upload Novo Documento
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>
    
       // =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHAS dd/mm/yyyy
        // =============================================================
        const expectedClosingDatePicker = document.getElementById('expected_closing_date_picker');
        const expectedClosingDateDisplay = document.getElementById('expected_closing_date_display');

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
        
        
        const openDatePicker = document.getElementById('open_date_picker');
        const openDateDisplay = document.getElementById('open_date_display');
        
        if (openDatePicker && openDateDisplay) {
            openDatePicker.addEventListener('change', function() {
                const selectedDate1 = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                openDateDisplay.textContent = formatDateForDisplay(selectedDate1);
            });
        }    
        
        if (openDatePicker.value && openDateDisplay.textContent.trim() === '') {
             openDateDisplay.textContent = formatDateForDisplay(openDatePicker.value);
        }        
        
        // =============================================================
        // SECCION PARA MANEJAR FORMATO DE SEPARADOR DECIMAL BRASILERO
        // =============================================================        

        const estimatedSale = document.getElementById('estimated_sale');
        const final_price = document.getElementById('final_price');

        // Función para formatear el número para visualización (coma decimal y puntos de miles)
        function formatNumberForDisplay(value) {
            if (value === null || value === undefined || value === '' || value === 0) {
                return '';
            }

            // Convertir a string si no lo es
            let stringValue = String(value);

            // Si ya contiene formato brasilero (coma decimal), parsearlo primero
            if (stringValue.includes(',')) {
                // Remover puntos de miles y convertir coma decimal a punto
                let cleanValue = stringValue.replace(/\./g, '').replace(',', '.');
                value = parseFloat(cleanValue);
            } else {
                value = parseFloat(stringValue);
            }

            if (isNaN(value)) {
                return '';
            }

            // Formatear con separadores brasileros (punto para miles, coma para decimales)
            return value.toLocaleString('pt-BR', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            });
        }

        // Función para limpiar y transformar la entrada a formato numérico estándar (para el backend)
        function cleanAndFormatForBackend(value) {
            if (value === null || value === undefined || value === '') {
                return '';
            }

            let stringValue = String(value);

            // Si ya es un número válido sin formato (ej: "1234.56"), devolverlo
            if (!stringValue.includes(',') && !stringValue.includes('.')) {
                return stringValue;
            }

            // Si contiene formato brasilero, convertir a formato estándar
            if (stringValue.includes(',')) {
                // Remover puntos de miles y convertir coma decimal a punto
                let cleanValue = stringValue.replace(/\./g, '').replace(',', '.');
                const num = parseFloat(cleanValue);
                return isNaN(num) ? '' : String(num);
            }

            // Si solo contiene puntos, asumir que el último es decimal si hay más de uno
            const dotCount = (stringValue.match(/\./g) || []).length;
            if (dotCount > 1) {
                // Múltiples puntos: tratar todos menos el último como separadores de miles
                const lastDotIndex = stringValue.lastIndexOf('.');
                const integerPart = stringValue.substring(0, lastDotIndex).replace(/\./g, '');
                const decimalPart = stringValue.substring(lastDotIndex + 1);
                const num = parseFloat(integerPart + '.' + decimalPart);
                return isNaN(num) ? '' : String(num);
            }

            // Un solo punto o sin puntos: asumir formato estándar
            const num = parseFloat(stringValue);
            return isNaN(num) ? '' : String(num);
        }

        // Event listener para permitir solo caracteres válidos mientras se escribe
        function handleInput(event) {
            let value = event.target.value;
            let cursorPosition = event.target.selectionStart;

            // Remover caracteres no válidos (solo permitir números, coma y punto)
            let cleanValue = value.replace(/[^\d.,]/g, '');

            // Permitir solo una coma decimal
            const commaCount = (cleanValue.match(/,/g) || []).length;
            if (commaCount > 1) {
                const firstCommaIndex = cleanValue.indexOf(',');
                cleanValue = cleanValue.substring(0, firstCommaIndex + 1) + 
                            cleanValue.substring(firstCommaIndex + 1).replace(/,/g, '');
            }

            // Limitar decimales a 2 dígitos después de la coma
            if (cleanValue.includes(',')) {
                const parts = cleanValue.split(',');
                if (parts[1] && parts[1].length > 2) {
                    cleanValue = parts[0] + ',' + parts[1].substring(0, 2);
                }
            }

            // Solo actualizar si el valor cambió
            if (value !== cleanValue) {
                event.target.value = cleanValue;
                // Mantener la posición del cursor
                event.target.setSelectionRange(cursorPosition, cursorPosition);
            }
        }

        // Event listener para formatear al salir del campo
        function handleBlur(event) {
            const value = event.target.value;
            if (value) {
                // Convertir a número y formatear
                const cleanValue = cleanAndFormatForBackend(value);
                const formattedValue = formatNumberForDisplay(cleanValue);
                event.target.value = formattedValue;
            }
        }

        // Event listener para limpiar el formato al enfocar (opcional - para mejor UX)
        function handleFocus(event) {
            const value = event.target.value;
            if (value) {
                // Mostrar el valor sin formato de miles para facilitar la edición
                const cleanValue = cleanAndFormatForBackend(value);
                const num = parseFloat(cleanValue);
                if (!isNaN(num)) {
                    // Mostrar con coma decimal pero sin puntos de miles
                    event.target.value = num.toFixed(2).replace('.', ',');
                }
            }
        }

        // Aplicar los event listeners a estimated_sale
        if (estimatedSale) {
            estimatedSale.addEventListener('input', handleInput);
            estimatedSale.addEventListener('blur', handleBlur);
            estimatedSale.addEventListener('focus', handleFocus);

            // Formatear el valor inicial al cargar la página
            if (estimatedSale.value) {
                const initialFormatted = formatNumberForDisplay(estimatedSale.value);
                estimatedSale.value = initialFormatted;
            }
        }
        
        
        // Aplicar los event listeners a final_price
        if (final_price) {
            final_price.addEventListener('input', handleInput);
            final_price.addEventListener('blur', handleBlur);
            final_price.addEventListener('focus', handleFocus);

            // Formatear el valor inicial al cargar la página
            if (final_price.value) {
                const initialFormatted = formatNumberForDisplay(final_price.value);
                final_price.value = initialFormatted;
            }
        }        
        
        
        

        // Manejar el envío del formulario - convertir a formato estándar para el backend
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(event) {
                if (estimatedSale && estimatedSale.value) {
                    // Convertir el valor al formato estándar antes de enviar
                    const standardValue = cleanAndFormatForBackend(estimatedSale.value);
                    estimatedSale.value = standardValue;
                }
            });
        }

        // Restaurar formato después de errores de validación (si hay errores y la página se recarga)
        document.addEventListener('DOMContentLoaded', function() {
            if (estimatedSale && estimatedSale.value) {
                const formattedValue = formatNumberForDisplay(estimatedSale.value);
                estimatedSale.value = formattedValue;
            }
        });
</script>

@endpush