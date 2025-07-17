@extends('layouts.app')

@section('page_title', 'Editar Oportunidad')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Oportunidad: {{ $opportunity->getOpportunityName() }}</h2>
            @if (session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            {{-- Formulario de Edición --}}
            <form action="{{ route('salesperson.opportunities.update', ['id' => $opportunity->getOpportunityId()]) }}" method="POST">
                @csrf
                @method('PUT') {{-- O PATCH, según tu preferencia para actualizaciones --}}

                {{-- Campo Oportunity Name --}}
                <div class="mb-4">
                    <label for="opportunity_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre de la Oportunidad:</label>
                    <input type="text" name="opportunity_name" id="opportunity_name"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('opportunity_name') border-red-500 @enderror"
                           value="{{ old('opportunity_name', $opportunity->getOpportunityName()) }}" required>
                    @error('opportunity_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Description --}}
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" rows="4"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $opportunity->getDescription()) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Estimated Sale --}}
                <div class="mb-4">
                    <label for="estimated_sale" class="block text-gray-700 text-sm font-bold mb-2">Venta Estimada:</label>
                    <input type="number" name="estimated_sale" id="estimated_sale" step="0.01"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estimated_sale') border-red-500 @enderror"
                           value="{{ old('estimated_sale', $opportunity->getEstimatedSale()) }}" required>
                    @error('estimated_sale')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Expected Closing Date --}}
                <div class="mb-4">
                    <label for="expected_closing_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Cierre Prevista:</label>
                    <input type="date" name="expected_closing_date" id="expected_closing_date"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('expected_closing_date') border-red-500 @enderror"
                           value="{{ old('expected_closing_date', $opportunity->getExpectedClosingDate() ? $opportunity->getExpectedClosingDate()->format('Y-m-d') : '') }}">
                    @error('expected_closing_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Currency --}}
                <div class="mb-4">
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Moneda (ej. USD):</label>
                    <input type="text" name="currency" id="currency" maxlength="3"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('currency') border-red-500 @enderror"
                           value="{{ old('currency', $opportunity->getCurrency()) }}">
                    @error('currency')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Lead Origin (Dropdown) --}}
                <div class="mb-4">
                    <label for="lead_origin_id" class="block text-gray-700 text-sm font-bold mb-2">Origen del Lead:</label>
                    <select name="lead_origin_id" id="lead_origin_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lead_origin_id') border-red-500 @enderror">
                        <option value="">Seleccione un origen</option>
                        @foreach ($leadOrigins as $origin)
                            <option value="{{ $origin->getLeadOriginId() }}"
                                {{ old('lead_origin_id', $opportunity->getLeadOrigin() ? $opportunity->getLeadOrigin()->getLeadOriginId() : '') == $origin->getLeadOriginId() ? 'selected' : '' }}>
                                {{ $origin->getOrigin() }} 
                            </option>
                        @endforeach
                    </select>
                    @error('lead_origin_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Priority (Dropdown) --}}
                <div class="mb-4">
                    <label for="priority" class="block text-gray-700 text-sm font-bold mb-2">Prioridad:</label>
                    <select name="priority" id="priority"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('priority') border-red-500 @enderror" required>
                        @php
                            $priorityOptions = [
                                'Low' => 'Baja',
                                'Medium' => 'Media',
                                'High' => 'Alta',
                                'Critical' => 'Crítica',
                            ];
                        @endphp
                        @foreach ($priorityOptions as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('priority', $opportunity->getPriority()) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Opportunity Status (Dropdown) --}}
                <div class="mb-6">
                    <label for="fk_op_status_id" class="block text-gray-700 text-sm font-bold mb-2">Estado de la Oportunidad:</label>
                    <select name="fk_op_status_id" id="fk_op_status_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fk_op_status_id') border-red-500 @enderror">
                        <option value="">Seleccione un estado</option>
                        @foreach ($opportunityStatuses as $status)
                            <option value="{{ $status->getOpportunityStatusId() }}"
                                {{ old('fk_op_status_id', $opportunity->getOpportunityStatus() ? $opportunity->getOpportunityStatus()->getOpportunityStatusId() : '') == $status->getOpportunityStatusId() ? 'selected' : '' }}>
                                {{ $status->getStatus() }} 
                            </option>
                        @endforeach
                    </select>
                    @error('fk_op_status_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campos de solo lectura --}}
                <hr class="my-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Información de Solo Lectura</h3>

                {{-- Open Date --}}
                <div class="mb-4">
                    <label for="open_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha de Apertura:</label>
                    <input type="text" id="open_date"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none"
                           value="{{ $opportunity->getOpenDate() ? $opportunity->getOpenDate()->format('Y-m-d') : 'N/A' }}" readonly>
                </div>

                {{-- Vendor --}}
                <div class="mb-4">
                    <label for="vendor" class="block text-gray-700 text-sm font-bold mb-2">Vendedor:</label>
                    <input type="text" id="vendor"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none"
                           value="{{ $opportunity->getVendor() ? ($opportunity->getVendor()->getVendorName() ?? 'N/A') : 'N/A' }}" readonly> 
                </div>

                {{-- Person --}}
                <div class="mb-4">
                    <label for="person" class="block text-gray-700 text-sm font-bold mb-2">Persona de Contacto:</label>
                    <input type="text" id="person"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none"
                           value="{{ $opportunity->getPerson() ? ($opportunity->getPerson()->getPersonName()  ?? 'N/A') : 'N/A' }}" readonly>
                </div>

                {{-- Current Stage (from latest StageHistory) --}}
                <div class="mb-6">
                    <label for="current_stage" class="block text-gray-700 text-sm font-bold mb-2">Etapa Actual:</label>
                    <input type="text" id="current_stage"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none"
                           value="{{ $currentStage ? ($currentStage->getStageName() ?? 'N/A') : 'No asignada' }}" readonly> 
                </div>

                {{-- Productos/Servicios Asociados --}}
                <hr class="my-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Productos/Servicios Asociados (Solo Lectura)</h3>
                @if (empty($prodServOpps))
                    <p class="text-gray-600">No hay productos o servicios asociados a esta oportunidad.</p>
                @else
                    <ul class="list-disc pl-5">
                        @foreach ($prodServOpps as $prodServOpp)
                            @php
                                $productService = $prodServOpp->getProductService();
                            @endphp
                            <li class="mb-2 text-gray-700">
                                <span class="font-semibold">{{ $productService->getName() }}</span>
                                (Tipo: {{ $productService->getType() ?? 'N/A' }}, Precio: {{ $productService->getPrice() ?? 'N/A' }} {{ $opportunity->getCurrency() ?? 'USD' }})
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- Botones de acción --}}
                <div class="flex items-center justify-between mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Actualizar Oportunidad
                    </button>
                    <a href="{{ url()->previous() }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
