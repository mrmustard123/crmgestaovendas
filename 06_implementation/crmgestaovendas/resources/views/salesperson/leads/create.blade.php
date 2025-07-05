<?php

/*
File: create
Author: Leonardo G. Tellez Saucedo
Created on: 4 jul. de 2025 16:54:05
Email: leonardo616@gmail.com
*/

?>

@extends('layouts.app')

@section('page_title', 'Registrar Novo Lead e Oportunidade')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Registrar Novo Lead e Oportunidade</h2>

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

            <form action="{{ route('salesperson.leads.store') }}" method="POST">
                @csrf

                {{-- Seção: Dados da Pessoa (Lead) --}}
                <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Dados do Lead (Pessoa)</h3>

                <div class="mb-4">
                    <label for="person_name" class="block text-gray-700 text-sm font-bold mb-2">Nome Completo do Lead:</label>
                    <input type="text" id="person_name" name="person_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('person_name') border-red-500 @enderror" value="{{ old('person_name') }}" required>
                    @error('person_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="main_phone" class="block text-gray-700 text-sm font-bold mb-2">Telefone Principal (Opcional):</label>
                        <input type="text" id="main_phone" name="main_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('main_phone') border-red-500 @enderror" value="{{ old('main_phone') }}">
                        @error('main_phone')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="main_email" class="block text-gray-700 text-sm font-bold mb-2">Email Principal (Opcional):</label>
                        <input type="email" id="main_email" name="main_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('main_email') border-red-500 @enderror" value="{{ old('main_email') }}">
                        @error('main_email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="job_position" class="block text-gray-700 text-sm font-bold mb-2">Cargo (Opcional):</label>
                    <input type="text" id="job_position" name="job_position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('job_position') border-red-500 @enderror" value="{{ old('job_position') }}">
                    @error('job_position')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Endereço (Opcional):</label>
                        <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('address') border-red-500 @enderror" value="{{ old('address') }}">
                        @error('address')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="complement" class="block text-gray-700 text-sm font-bold mb-2">Complemento (Opcional):</label>
                        <input type="text" id="complement" name="complement" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('complement') border-red-500 @enderror" value="{{ old('complement') }}">
                        @error('complement')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="neighborhood" class="block text-gray-700 text-sm font-bold mb-2">Bairro (Opcional):</label>
                        <input type="text" id="neighborhood" name="neighborhood" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('neighborhood') border-red-500 @enderror" value="{{ old('neighborhood') }}">
                        @error('neighborhood')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Cidade (Opcional):</label>
                        <input type="text" id="city" name="city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('city') border-red-500 @enderror" value="{{ old('city') }}">
                        @error('city')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="state" class="block text-gray-700 text-sm font-bold mb-2">Estado (UF, Opcional):</label>
                        <input type="text" id="state" name="state" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('state') border-red-500 @enderror" value="{{ old('state') }}">
                        @error('state')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">País:</label>
                    <input type="text" id="country" name="country" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('country') border-red-500 @enderror" value="{{ old('country', 'Brasil') }}" required>
                    @error('country')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="lead_origin_id" class="block text-gray-700 text-sm font-bold mb-2">Origem do Lead:</label>
                    <select id="lead_origin_id" name="lead_origin_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lead_origin_id') border-red-500 @enderror" required>
                        <option value="">Selecione uma origem</option>
                        @foreach ($leadOrigins as $origin)
                            <option value="{{ $origin->getLeadOriginId() }}" {{ old('lead_origin_id') == $origin->getLeadOriginId() ? 'selected' : '' }}>
                                {{ $origin->getOrigin() }}
                            </option>
                        @endforeach
                    </select>
                    @error('lead_origin_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Checkbox para indicar si es representante de compañía --}}
                <div class="mb-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="is_company_representative" name="is_company_representative" class="form-checkbox h-5 w-5 text-blue-600" value="1" {{ old('is_company_representative') ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">O Lead representa uma Companhia (Pessoa Jurídica)?</span>
                    </label>
                </div>

                {{-- Seção: Dados da Companhia (aparece condicionalmente) --}}
                <div id="company_fields" class="border p-4 rounded-lg bg-gray-50 {{ old('is_company_representative') ? '' : 'hidden' }}">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Dados da Companhia (Pessoa Jurídica)</h3>

                    <div class="mb-4">
                        <label for="social_reason" class="block text-gray-700 text-sm font-bold mb-2">Razão Social:</label>
                        <input type="text" id="social_reason" name="social_reason" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('social_reason') border-red-500 @enderror" value="{{ old('social_reason') }}">
                        @error('social_reason')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="fantasy_name" class="block text-gray-700 text-sm font-bold mb-2">Nome Fantasia (Opcional):</label>
                        <input type="text" id="fantasy_name" name="fantasy_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('fantasy_name') border-red-500 @enderror" value="{{ old('fantasy_name') }}">
                        @error('fantasy_name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="cnpj" class="block text-gray-700 text-sm font-bold mb-2">CNPJ (Opcional):</label>
                            <input type="text" id="cnpj" name="cnpj" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('cnpj') border-red-500 @enderror" value="{{ old('cnpj') }}">
                            @error('cnpj')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="inscricao_estadual" class="block text-gray-700 text-sm font-bold mb-2">Inscrição Estadual (Opcional):</label>
                            <input type="text" id="inscricao_estadual" name="inscricao_estadual" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('inscricao_estadual') border-red-500 @enderror" value="{{ old('inscricao_estadual') }}">
                            @error('inscricao_estadual')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="inscricao_municipal" class="block text-gray-700 text-sm font-bold mb-2">Inscrição Municipal (Opcional):</label>
                            <input type="text" id="inscricao_municipal" name="inscricao_municipal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('inscricao_municipal') border-red-500 @enderror" value="{{ old('inscricao_municipal') }}">
                            @error('inscricao_municipal')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="company_phone" class="block text-gray-700 text-sm font-bold mb-2">Telefone da Companhia (Opcional):</label>
                            <input type="text" id="company_phone" name="company_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_phone') border-red-500 @enderror" value="{{ old('company_phone') }}">
                            @error('company_phone')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="company_email" class="block text-gray-700 text-sm font-bold mb-2">Email da Companhia (Opcional):</label>
                        <input type="email" id="company_email" name="company_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_email') border-red-500 @enderror" value="{{ old('company_email') }}">
                        @error('company_email')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="company_address" class="block text-gray-700 text-sm font-bold mb-2">Endereço da Companhia (Opcional):</label>
                            <input type="text" id="company_address" name="company_address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_address') border-red-500 @enderror" value="{{ old('company_address') }}">
                            @error('company_address')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="company_complement" class="block text-gray-700 text-sm font-bold mb-2">Complemento da Companhia (Opcional):</label>
                            <input type="text" id="company_complement" name="company_complement" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_complement') border-red-500 @enderror" value="{{ old('company_complement') }}">
                            @error('company_complement')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-4">
                            <label for="company_neighborhood" class="block text-gray-700 text-sm font-bold mb-2">Bairro da Companhia (Opcional):</label>
                            <input type="text" id="company_neighborhood" name="company_neighborhood" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_neighborhood') border-red-500 @enderror" value="{{ old('company_neighborhood') }}">
                            @error('company_neighborhood')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="company_city" class="block text-gray-700 text-sm font-bold mb-2">Cidade da Companhia (Opcional):</label>
                            <input type="text" id="company_city" name="company_city" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_city') border-red-500 @enderror" value="{{ old('company_city') }}">
                            @error('company_city')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="company_state" class="block text-gray-700 text-sm font-bold mb-2">Estado da Companhia (UF, Opcional):</label>
                            <input type="text" id="company_state" name="company_state" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_state') border-red-500 @enderror" value="{{ old('company_state') }}">
                            @error('company_state')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="company_country" class="block text-gray-700 text-sm font-bold mb-2">País da Companhia:</label>
                        <input type="text" id="company_country" name="company_country" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_country') border-red-500 @enderror" value="{{ old('company_country', 'Brasil') }}">
                        @error('company_country')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="company_comments" class="block text-gray-700 text-sm font-bold mb-2">Comentários da Companhia (Opcional):</label>
                        <textarea id="company_comments" name="company_comments" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('company_comments') border-red-500 @enderror">{{ old('company_comments') }}</textarea>
                        @error('company_comments')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fin de company_fields --}}

                {{-- Seção: Dados da Oportunidade --}}
                <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4 border-b pb-2">Dados da Oportunidade Inicial</h3>

                <div class="mb-4">
                    <label for="opportunity_name" class="block text-gray-700 text-sm font-bold mb-2">Nome da Oportunidade:</label>
                    <input type="text" id="opportunity_name" name="opportunity_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('opportunity_name') border-red-500 @enderror" value="{{ old('opportunity_name') }}" required>
                    @error('opportunity_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição da Oportunidade (Opcional):</label>
                    <textarea id="description" name="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="estimated_sale" class="block text-gray-700 text-sm font-bold mb-2">Valor Estimado da Venda:</label>
                        <input type="number" step="0.01" id="estimated_sale" name="estimated_sale" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estimated_sale') border-red-500 @enderror" value="{{ old('estimated_sale', '0.00') }}" required>
                        @error('estimated_sale')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Moeda (Ex: BRL):</label>
                        <input type="text" id="currency" name="currency" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('currency') border-red-500 @enderror" value="{{ old('currency', 'BRL') }}">
                        @error('currency')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="expected_closing_date" class="block text-gray-700 text-sm font-bold mb-2">Data de Fechamento Esperada (Opcional):</label>
                        <input type="date" id="expected_closing_date" name="expected_closing_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('expected_closing_date') border-red-500 @enderror" value="{{ old('expected_closing_date') }}">
                        @error('expected_closing_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Registrar Lead e Oportunidade
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
        const companyRepresentativeCheckbox = document.getElementById('is_company_representative');
        const companyFieldsDiv = document.getElementById('company_fields');

        function toggleCompanyFields() {
            if (companyRepresentativeCheckbox.checked) {
                companyFieldsDiv.classList.remove('hidden');
                // Si la casilla está marcada, hacer que los campos obligatorios de compañía sean realmente obligatorios en el frontend
                document.getElementById('social_reason').setAttribute('required', 'required');
                document.getElementById('company_country').setAttribute('required', 'required');
            } else {
                companyFieldsDiv.classList.add('hidden');
                // Si la casilla no está marcada, eliminar el atributo 'required' y limpiar los valores
                document.getElementById('social_reason').removeAttribute('required');
                document.getElementById('company_country').removeAttribute('required');

                // Opcional: Limpiar los campos cuando se ocultan
                const companyInputs = companyFieldsDiv.querySelectorAll('input, select, textarea');
                companyInputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.checked = false;
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });
            }
        }

        // Ejecutar al cargar la página para reflejar el estado de `old()`
        toggleCompanyFields();

        // Añadir el listener para el cambio del checkbox
        companyRepresentativeCheckbox.addEventListener('change', toggleCompanyFields);
    });
</script>
@endpush


