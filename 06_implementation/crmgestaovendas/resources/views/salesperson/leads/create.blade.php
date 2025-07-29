<?php

/*
File: create
Author: Leonardo G. Tellez Saucedo
Created on: 4 jul. de 2025 16:54:05

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

                <div class="mb-4 relative">
                    <label for="person_name" class="block text-gray-700 text-sm font-bold mb-2">Nome Completo do Lead:</label>
                    <input type="text" id="person_name" name="person_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('person_name') border-red-500 @enderror" value="{{ old('person_name') }}" autocomplete="off" required>

                   {{-- Campo oculto para el ID de la Persona (si existe) --}}
                    <input type="hidden" id="person_id" name="person_id" value="">

                    {{-- Contenedor para las sugerencias de autocompletado --}}
                    <div id="person-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow-lg mt-1 hidden">
                        {{-- Las sugerencias se cargarán aquí --}}
                    </div>

                    {{-- Botón para limpiar selección si se ha cargado un lead existente --}}
                    <button type="button" id="clear-person-selection" class="mt-2 text-sm text-blue-600 hover:text-blue-800 hidden">
                        Limpiar selección y crear nueva persona
                    </button>                    
                    
                    
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
                        <input type="checkbox" id="is_company_representative_display" class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_company_representative') ? 'checked' : '' }}>                        
                        <input type="hidden" name="is_company_representative" id="is_company_representative_hidden" value="{{ old('is_company_representative') ? '1' : '0' }}">                        
                        <span class="ml-2 text-gray-700">O Lead representa uma Companhia (Pessoa Jurídica)?</span>
                    </label>
                </div>

                {{-- Seção: Dados da Companhia (aparece condicionalmente) --}}
                <div id="company_fields" class="border p-4 rounded-lg bg-gray-50 {{ old('is_company_representative') ? '' : 'hidden' }}">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Dados da Companhia (Pessoa Jurídica)</h3>                   
                    <div class="mb-4 relative">
                        <label for="social_reason" class="block text-gray-700 text-sm font-bold mb-2">Razão Social:</label>
                        <input type="text" id="social_reason" name="social_reason" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('social_reason') border-red-500 @enderror" value="{{ old('social_reason') }}" autocomplete="off">
                        
                        {{-- Campo oculto para el ID de la Compañía (si existe) --}}
                        <input type="hidden" id="company_id" name="company_id" value="">                        

                        {{-- Contenedor para las sugerencias de autocompletado de compañía --}}
                        <div id="company-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow-lg mt-1 hidden">
                            {{-- Las sugerencias de compañía se cargarán aquí --}}
                        </div>

                        {{-- Botón para limpiar selección de compañía --}}
                        <button type="button" id="clear-company-selection" class="mt-2 text-sm text-blue-600 hover:text-blue-800 hidden">
                            Limpiar selección de compañía
                        </button>                                                                        
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="estimated_sale" class="block text-gray-700 text-sm font-bold mb-2">Valor Estimado da Venda:</label>
                        <input type="text" id="estimated_sale" name="estimated_sale" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('estimated_sale') border-red-500 @enderror" value="{{ old('estimated_sale') }}" required>                        
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
                                             
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                
                    {{-- SECCIÓN PARA PRODUCTOS/SERVICIOS --}}
                    <div class="mb-4">
                        <label for="product_service_search" class="block text-gray-700 text-sm font-bold mb-2">Buscar Produtos/Serviços:</label>
                        <div id="selected_products_container" class="mt-2 flex flex-wrap gap-2">Prod/serv:
                        </div>                        
                        <input type="text" id="product_service_search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Digite pelo menos 3 caracteres para pesquisar..">
                        <div id="product_service_results" class="bg-white border rounded shadow mt-2 max-h-48 overflow-y-auto hidden z-10 relative">
                        </div>
                    </div>                    
                    
                    <div class="mb-4">
                        <label for="expected_closing_date_picker" class="block text-gray-700 text-sm font-bold mb-2">Data de Fechamento Esperada (Opcional):</label>
                            <span id="expected_closing_date_display" class="ml-2 text-gray-700 font-medium">
                                @if(old('expected_closing_date'))
                                    {{ \Carbon\Carbon::parse(old('expected_closing_date'))->format('d/m/Y') }}
                                @endif
                            </span>
                        <div class="flex items-center">
                            <input type="date"
                                   id="expected_closing_date_picker"
                                   name="expected_closing_date" {{-- ¡IMPORTANTE: este es el "name" que Laravel leerá! --}}
                                   class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline @error('expected_closing_date') border-red-500 @enderror bg-white" {{-- <-- Clases importantes: text-white y bg-white --}}
                                   value="{{ old('expected_closing_date') }}">

                        </div>
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
        const companyRepresentativeCheckbox = document.getElementById('is_company_representative_display');
        const companyRepresentativeHiddenInput = document.getElementById('is_company_representative_hidden');
        const companyFieldsDiv = document.getElementById('company_fields');
        
       /* companyRepresentativeCheckbok.addEventListener('click',function(){
            companyRepresentativeHiddenInput.value = companyRepresentativeCheckbox.value;
        });*/

        function toggleCompanyFields() {
            
            if (companyRepresentativeCheckbox.checked) {
                companyFieldsDiv.classList.remove('hidden');
                companyRepresentativeHiddenInput.value=1;
                // Si la casilla está marcada, hacer que los campos obligatorios de compañía sean realmente obligatorios en el frontend
                document.getElementById('social_reason').setAttribute('required', 'required');
                document.getElementById('company_country').setAttribute('required', 'required');
            } else {
                companyFieldsDiv.classList.add('hidden');
                // Si la casilla no está marcada, eliminar el atributo 'required' y limpiar los valores
                companyRepresentativeHiddenInput.value=0;
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
            //console.log(companyRepresentativeHiddenInput.value); para chequear el company_representative
        }

        // Ejecutar al cargar la página para reflejar el estado de `old()`
        //toggleCompanyFields();

        // Añadir el listener para el cambio del checkbox
        companyRepresentativeCheckbox.addEventListener('change', toggleCompanyFields);
        
        // =============================================================
        // NUEVO CÓDIGO PARA LA BÚSQUEDA Y SELECCIÓN DE PRODUCTOS/SERVICIOS
        // =============================================================
        const searchInput = document.getElementById('product_service_search');
        const resultsDiv = document.getElementById('product_service_results');
        const selectedProductsContainer = document.getElementById('selected_products_container');

        let selectedProductIds = new Set(); // Usamos un Set para asegurar IDs únicos y fácil manejo de adición/eliminación

        // Función para añadir un producto seleccionado como "tag" y su input oculto
        function addSelectedProductTag(id, name) {
            if (!selectedProductIds.has(id)) { // Solo añade si no está ya seleccionado
                selectedProductIds.add(id);

                // Crea el elemento visual (el "tag")
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 cursor-pointer';
                tag.innerHTML = `${name} <button type="button" class="ml-2 text-blue-500 hover:text-blue-700 focus:outline-none" data-id="${id}">&times;</button>`;
                selectedProductsContainer.appendChild(tag);

                // Crea el input oculto para enviar el ID al backend
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selected_product_services[]'; // El [] es crucial para que Laravel lo reciba como un array
                hiddenInput.value = id;
                hiddenInput.dataset.productId = id; // Atributo personalizado para facilitar su búsqueda al eliminar
                selectedProductsContainer.appendChild(hiddenInput); // Lo añadimos al mismo contenedor que los tags

                // Añade el listener para eliminar el tag y el input oculto
                tag.querySelector('button').addEventListener('click', function() {
                    removeSelectedProductTag(id, tag, hiddenInput);
                });
            }
        }

        // Función para eliminar un producto seleccionado (tag y input oculto)
        function removeSelectedProductTag(id, tagElement, hiddenInputElement) {
            selectedProductIds.delete(id); // Elimina el ID del Set
            tagElement.remove(); // Elimina el tag visual
            hiddenInputElement.remove(); // Elimina el input oculto asociado
        }

        let searchTimeout; // Para el debounce de la búsqueda

        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout); // Limpia cualquier timeout anterior

            const searchTerm = searchInput.value.trim();

            if (searchTerm.length >= 3) {
                searchTimeout = setTimeout(() => { // Introduce un pequeño retraso (debounce)
                    // Realiza la petición AJAX
                    fetch(`{{ url('/') }}/api/product-services/search?term=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = ''; // Limpia resultados anteriores
                            if (data.length > 0) {
                                data.forEach(item => {
                                    // Solo muestra items que no estén ya seleccionados
                                    if (!selectedProductIds.has(item.id)) {
                                        const resultItem = document.createElement('div');
                                        resultItem.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0';
                                        resultItem.textContent = item.text;
                                        resultItem.dataset.id = item.id;
                                        resultItem.dataset.name = item.text;

                                        resultItem.addEventListener('click', function() {
                                            addSelectedProductTag(item.id, item.text);
                                            searchInput.value = ''; // Limpia el input de búsqueda
                                            resultsDiv.classList.add('hidden'); // Oculta los resultados
                                            resultsDiv.innerHTML = ''; // Limpia los resultados mostrados
                                        });
                                        resultsDiv.appendChild(resultItem);
                                    }
                                });
                                resultsDiv.classList.remove('hidden'); // Muestra el contenedor de resultados
                            } else {
                                resultsDiv.classList.add('hidden'); // Oculta si no hay resultados
                            }
                        })
                        .catch(error => console.error('Error al buscar productos/servicios:', error));
                }, 300); // Espera 300ms después de la última pulsación
            } else {
                resultsDiv.classList.add('hidden'); // Oculta si el término es muy corto
                resultsDiv.innerHTML = '';
            }
        });

        // Ocultar los resultados al hacer clic fuera del campo de búsqueda y de los resultados
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !resultsDiv.contains(event.target)) {
                resultsDiv.classList.add('hidden');
                resultsDiv.innerHTML = '';
            }
        });
        
        
        // =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHA dd/mm/yyyy
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
        
// =============================================================
        // SECCION PARA MANEJAR FORMATO DE SEPARADOR DECIMAL BRASILERO
        // =============================================================        

        const estimatedSale = document.getElementById('estimated_sale');

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

        // Aplicar los event listeners
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
        
        // =============================================================
        // SECCION PARA OBTENER SUGERENCIAS DE LEADS YA REGISTRADOS
        // =============================================================         
        
        const personNameInput = document.getElementById('person_name');
        const personIdInput = document.getElementById('person_id');
        const personSuggestionsDiv = document.getElementById('person-suggestions');
        const clearPersonSelectionBtn = document.getElementById('clear-person-selection');

        // Campos de la persona a rellenar
        const personFields = {
            'main_phone': document.getElementById('main_phone'),
            'main_email': document.getElementById('main_email'),
            'address': document.getElementById('address'), // Asume id 'address' para persona
            'complement': document.getElementById('complement'), // Asume id 'complement' para persona
            'job_position': document.getElementById('job_position'),
            /*'person_role_id': document.getElementById('person_role_id'), // Select  */
            'neighborhood': document.getElementById('neighborhood'),
            'city': document.getElementById('city'),
            'state': document.getElementById('state'),
            'country': document.getElementById('country'),
        };

        // Campos de la compañía a rellenar
        const companyFields = {
            'is_company_representative_hidden': document.getElementById('is_company_representative_hidden'), // input hidden
            'is_company_representative_display': document.getElementById('is_company_representative_display'), // input hidden
            'company_fields': document.getElementById('company_fields'), // Div contenedor
            'social_reason': document.getElementById('social_reason'),
            'fantasy_name': document.getElementById('fantasy_name'),
            'cnpj': document.getElementById('cnpj'),
            'inscricao_estadual': document.getElementById('inscricao_estadual'),
            'inscricao_municipal': document.getElementById('inscricao_municipal'),
            'main_phone': document.getElementById('company_phone'), // Asume id 'company_main_phone'
            'main_email': document.getElementById('company_email'), // Asume id 'company_main_email'
            'address': document.getElementById('company_address'),
            'complement': document.getElementById('company_complement'),
            'neighborhood': document.getElementById('company_neighborhood'),
            'city': document.getElementById('company_city'),
            'state': document.getElementById('company_state'),
            'country': document.getElementById('company_country'),
            /*'status': document.getElementById('company_status'), // Select*/
            'comments': document.getElementById('company_comments'), // Textarea
        };

        let debounceTimeout;

        // Función para rellenar los campos del formulario con los datos de la persona/compañía seleccionada
        function populateForm(personData) {
            // Rellenar campos de la persona
            personIdInput.value = personData.id;
            personNameInput.value = personData.name;
            //personNameInput.readOnly = true; // Hacer el campo de nombre de solo lectura
            clearPersonSelectionBtn.classList.remove('hidden'); // Mostrar botón para limpiar

            for (const field in personFields) {
                if (personFields[field] && personData[field] !== undefined && personData[field] !== null) {
                    if (personFields[field].tagName === 'SELECT') {
                        // Para select, encontrar la opción por valor
                        const option = Array.from(personFields[field].options).find(opt => opt.value == personData[field]);
                        if (option) {
                            personFields[field].value = option.value;
                        }
                    } else {
                        personFields[field].value = personData[field];
                    }
                    personFields[field].readOnly = true; // Hacer los campos de persona de solo lectura
                    personFields[field].style.backgroundColor = '#f0f0f0'; // Añadir un estilo visual
                }
            }

            // Rellenar campos de la compañía si existen datos
            if (personData.company) {
                companyFields.is_company_representative_display.checked = true;
                companyFields.is_company_representative_hidden.value = 1;
                companyFields.company_fields.classList.remove('hidden'); // Asegurarse de que el div esté visible
                
                // Set the company_id if it exists
                const companyIdInput = document.getElementById('company_id'); // Assuming you'll add this hidden input
                if (companyIdInput) {
                    companyIdInput.value = personData.company.id;
                }

                for (const field in companyFields) {
                    if (field === 'is_company_representative_hidden' || field === 'company_fields' || field === 'is_company_representative_display') continue; // Saltar estos campos
                    if (companyFields[field] && personData.company[field] !== undefined && personData.company[field] !== null) {
                        if (companyFields[field].tagName === 'SELECT') {
                            const option = Array.from(companyFields[field].options).find(opt => opt.value == personData.company[field]);
                            if (option) {
                                companyFields[field].value = option.value;
                            }
                        } else {
                            companyFields[field].value = personData.company[field];
                        }
                        companyFields[field].readOnly = true; // Hacer los campos de compañía de solo lectura
                        companyFields[field].style.backgroundColor = '#f0f0f0'; // Añadir un estilo visual
                    }
                }
                // Deshabilitar el checkbox para que no se pueda desmarcar fácilmente
                companyFields.is_company_representative_display.disabled = true;
            } else {
                // Si no hay compañía, asegurarse de que el checkbox esté desmarcado y los campos ocultos/vacíos
                companyFields.is_company_representative_display.checked = false;
                companyFields.is_company_representative_hidden.value = 0;
                companyFields.company_fields.classList.add('hidden');
                companyFields.is_company_representative_display.disabled = false; // Habilitar si no hay compañía cargada
                 // Limpiar campos de compañía
                 
                 /*TESTAR ESTA SECCION DE CODIGO*/
                for (const field in companyFields) {
                    if (field === 'is_company_representative_display' || field === 'is_company_representative_hidden' || field === 'company_fields') continue;
                    if (companyFields[field]) {
                        companyFields[field].value = '';
                        companyFields[field].readOnly = false;
                        companyFields[field].style.backgroundColor = '';
                    }
                }
                /***********************************/
                const companyIdInput = document.getElementById('company_id');
                if (companyIdInput) companyIdInput.value = '';
            }
            
            // Ocultar sugerencias
            personSuggestionsDiv.classList.add('hidden');
        }

        // Función para limpiar el formulario y permitir la entrada de una nueva persona
        function clearFormForNewPerson() {
            personIdInput.value = '';
            personNameInput.readOnly = false;
            personNameInput.value = ''; // Limpiar el nombre para que el usuario pueda escribir de nuevo
            clearPersonSelectionBtn.classList.add('hidden');

            // Limpiar y habilitar campos de la persona
            for (const field in personFields) {
                if (personFields[field]) {
                    personFields[field].value = '';
                    personFields[field].readOnly = false;
                    personFields[field].style.backgroundColor = '';
                }
            }

            // Limpiar y deshabilitar campos de la compañía
            companyFields.is_company_representative_display.checked = false;
            companyFields.is_company_representative_hidden.value = 0;            
            companyFields.is_company_representative_display.disabled = false;
            companyFields.company_fields.classList.add('hidden'); // Ocultar campos de compañía
            
            const companyIdInput = document.getElementById('company_id');
            if (companyIdInput) companyIdInput.value = '';

            for (const field in companyFields) {
                if (field === 'is_company_representative_display' || field === 'is_company_representative_hidden' || field === 'company_fields') continue;
                if (companyFields[field]) {
                    companyFields[field].value = '';
                    companyFields[field].readOnly = false;
                    companyFields[field].style.backgroundColor = '';
                }
            }
            personNameInput.focus(); // Devolver el foco al campo de nombre
        }

        // Listener para el botón de limpiar selección
        clearPersonSelectionBtn.addEventListener('click', clearFormForNewPerson);


        // Escuchar la entrada en el campo de nombre
        personNameInput.addEventListener('input', function () {
            clearTimeout(debounceTimeout); // Limpiar cualquier timeout anterior

            const query = this.value.trim();

            if (query.length < 3) {
                personSuggestionsDiv.classList.add('hidden'); // Ocultar si menos de 3 caracteres
                return;
            }

            debounceTimeout = setTimeout(() => {
                fetch(`{{ url('/') }}/api/search-person?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        personSuggestionsDiv.innerHTML = ''; // Limpiar sugerencias anteriores
                        if (data.length > 0) {
                            data.forEach(person => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                                let displayText = person.name;
                                if (person.main_email) {
                                    displayText += ` (${person.main_email})`;
                                } else if (person.main_phone) {
                                    displayText += ` (${person.main_phone})`;
                                }
                                suggestionItem.textContent = displayText;
                                
                                // Al hacer clic en una sugerencia
                                suggestionItem.addEventListener('click', function () {
                                    populateForm(person);
                                });
                                personSuggestionsDiv.appendChild(suggestionItem);
                            });
                            personSuggestionsDiv.classList.remove('hidden');
                        } else {
                            personSuggestionsDiv.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching person suggestions:', error);
                        personSuggestionsDiv.classList.add('hidden');
                    });
            }, 300); // Retraso de 300ms
        });

        // Ocultar sugerencias al hacer clic fuera del área de autocompletado
        document.addEventListener('click', function (event) {
            if (!personNameInput.contains(event.target) && !personSuggestionsDiv.contains(event.target)) {
                personSuggestionsDiv.classList.add('hidden');
            }
        });

        const companyFieldsContainer = document.getElementById('company_fields');       


        // ============== LOGICA PARA AUTOCOMPLETADO DE COMPANY =============

        const socialReasonInput = document.getElementById('social_reason');
        const companyIdInput = document.getElementById('company_id');
        const companySuggestionsDiv = document.getElementById('company-suggestions');
        const clearCompanySelectionBtn = document.getElementById('clear-company-selection');
        
        //companyRepresentativeCheckbox.checked = true;
                   

        let companyDebounceTimeout;

        // Función para rellenar los campos de la compañía del formulario
        function populateCompanyForm(companyData) {
            companyIdInput.value = companyData.id;
            socialReasonInput.value = companyData.social_reason;
            socialReasonInput.readOnly = true; // Hacer el campo de solo lectura
            clearCompanySelectionBtn.classList.remove('hidden'); // Mostrar botón para limpiar

            // Asegurarse de que el checkbox "Representa una compañía?" esté marcado y deshabilitado
            companyFields.is_company_representative_display.checked = true;
            companyFields.is_company_representative_hidden.value = 1;
            companyFields.is_company_representative_display.disabled = true;
            // Asegurarse de que el contenedor de campos de compañía esté visible
            companyFields.company_fields.classList.remove('hidden');

            /*COLOREANDO LOS CAMPOS*/
            for (const field in companyData) { // Iterar sobre los datos recibidos
                // Mapear los campos del backend a los IDs del frontend
                let frontendFieldId = field;
                if (field === 'main_phone') frontendFieldId = 'company_phone';
                if (field === 'main_email') frontendFieldId = 'company_email';
                if (field === 'address') frontendFieldId = 'company_address';
                if (field === 'complement') frontendFieldId = 'company_complement';
                if (field === 'neighborhood') frontendFieldId = 'company_neighborhood';
                if (field === 'city') frontendFieldId = 'company_city';
                if (field === 'state') frontendFieldId = 'company_state';
                if (field === 'country') frontendFieldId = 'company_country';
                if (field === 'status') frontendFieldId = 'company_status';
                if (field === 'comments') frontendFieldId = 'company_comments';
                if (field === 'social_reason' || field === 'fantasy_name' || field === 'cnpj' || field === 'inscricao_estadual' || field === 'inscricao_municipal' || frontendFieldId === 'id') {
                    // Estos ya se manejan directamente por su nombre o se saltan (id)
                    // Si el campo tiene un ID directo en companyFields, lo usamos.
                    // Los campos especiales como main_phone, main_email, etc. ya tienen un mapeo arriba
                    // en companyFields, pero los otros se asignan directamente si existen.
                }

                const targetElement = document.getElementById(frontendFieldId);
                
                if (targetElement && companyData[field] !== undefined && companyData[field] !== null) {
                    if (targetElement.tagName === 'SELECT') {
                        const option = Array.from(targetElement.options).find(opt => opt.value == companyData[field]);
                        if (option) {
                            targetElement.value = option.value;
                        }
                    } else {
                        targetElement.value = companyData[field];
                    }
                    targetElement.readOnly = true;
                    targetElement.style.backgroundColor = '#f0f0f0';
                }
            }/*END FOR "COLOREANDO LOS CAMPOS*/
            companySuggestionsDiv.classList.add('hidden');
        }

        // Función para limpiar los campos de la compañía y permitir la entrada de una nueva compañía
        function clearCompanyFormForNew() {
            companyIdInput.value = '';
            socialReasonInput.readOnly = false;
            socialReasonInput.value = '';
            clearCompanySelectionBtn.classList.add('hidden');            
            companyFields.is_company_representative_display.disabled = false; // Habilitar el checkbox
            // No ocultar el contenedor directamente, el toggleCompanyFields se encargará si el checkbox se desmarca.
            // companyFields.company_fields_container.classList.add('hidden'); // No hacer esto aquí

            for (const field in companyFields) {
                const targetElement = companyFields[field]; // Usar la referencia directa
                if (targetElement && field !== 'is_company_representative_display' && field !== 'company_fields') {
                    if (targetElement.tagName === 'SELECT') {
                        targetElement.selectedIndex = 0; // Resetear select
                    } else {
                        targetElement.value = '';
                    }
                    targetElement.readOnly = false;
                    targetElement.style.backgroundColor = '';
                }
            }
            socialReasonInput.focus();
        }

        // Listener para el botón de limpiar selección de compañía
        clearCompanySelectionBtn.addEventListener('click', clearCompanyFormForNew);

        // Escuchar la entrada en el campo de razón social
        socialReasonInput.addEventListener('input', function () {
            clearTimeout(companyDebounceTimeout);

            const query = this.value.trim();

            if (query.length < 3) {
                companySuggestionsDiv.classList.add('hidden');
                return;
            }

            companyDebounceTimeout = setTimeout(() => {
                fetch(`{{ url('/') }}/api/search-company?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        companySuggestionsDiv.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(company => {
                                const suggestionItem = document.createElement('div');
                                suggestionItem.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-200');
                                let displayText = company.social_reason;
                                if (company.fantasy_name) {
                                    displayText += ` (${company.fantasy_name})`;
                                }
                                if (company.cnpj) {
                                    displayText += ` - CNPJ: ${company.cnpj}`;
                                }
                                suggestionItem.textContent = displayText;
                                
                                suggestionItem.addEventListener('click', function () {
                                    populateCompanyForm(company);
                                });
                                companySuggestionsDiv.appendChild(suggestionItem);
                            });
                            companySuggestionsDiv.classList.remove('hidden');
                        } else {
                            companySuggestionsDiv.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching company suggestions:', error);
                        companySuggestionsDiv.classList.add('hidden');
                    });
            }, 300); // Retraso de 300ms
        });

        // Ocultar sugerencias al hacer clic fuera del área de autocompletado de compañía
        document.addEventListener('click', function (event) {
            if (!socialReasonInput.contains(event.target) && !companySuggestionsDiv.contains(event.target)) {
                companySuggestionsDiv.classList.add('hidden');
            }
        });
        
                             
                     
    });
</script>
@endpush


