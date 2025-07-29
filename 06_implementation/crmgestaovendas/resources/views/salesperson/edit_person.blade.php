<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
?>
@extends('layouts.app')

@section('page_title', 'Editar Pessoa')    
    
@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            Editar Lead: <span class="text-blue-600">{{ $person->getPersonName() }}</span>
        </h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erro!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline">Houve alguns problemas com seus dados.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('salesperson.persons.update', ['personId' => $person->getPersonId()]) }}" method="POST">
            @csrf
            @method('PUT') {{-- Usa o método PUT para atualização --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Nome da Pessoa --}}
                <div>
                    <label for="person_name" class="block text-gray-700 text-sm font-bold mb-2">Nome Completo:</label>
                    <input type="text" id="person_name" name="person_name" value="{{ old('person_name', $person->getPersonName()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                {{-- Email Principal --}}
                <div>
                    <label for="main_email" class="block text-gray-700 text-sm font-bold mb-2">Email Principal:</label>
                    <input type="email" id="main_email" name="main_email" value="{{ old('main_email', $person->getMainEmail()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Telefone Principal --}}
                <div>
                    <label for="main_phone" class="block text-gray-700 text-sm font-bold mb-2">Telefone Principal:</label>
                    <input type="text" id="main_phone" name="main_phone" value="{{ old('main_phone', $person->getMainPhone()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- CPF --}}
                <div>
                    <label for="cpf" class="block text-gray-700 text-sm font-bold mb-2">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $person->getCpf()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- RG --}}
                <div>
                    <label for="rg" class="block text-gray-700 text-sm font-bold mb-2">RG:</label>
                    <input type="text" id="rg" name="rg" value="{{ old('rg', $person->getRg()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Data de Nascimento --}}
                <div>
                    <label for="birthdate_picker" class="block text-gray-700 text-sm font-bold mb-2">Data de Nascimento:</label>
                    <span id="birthdate_display" class="ml-2 text-gray-700 font-medium">                         
                                @if(old('expected_closing_date'))
                                    {{ \Carbon\Carbon::parse(old('expected_closing_date'))->format('d/m/Y') }}
                                @else
                                    {{ $person->getBirthdate() }}
                                @endif                         
                     </span>
                    <div class="flex items-center">
                        <input type="date" id="birthdate_picker" name="birthdate" value="{{ old('birthdate', $person->getBirthdate() ? $person->getBirthdate()->format('Y-m-d') : '') }}"
                           class="shadow appearance-none border rounded w-40 py-2 px-3 text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                {{-- Sexo --}}
                <div>
                    <label for="sex" class="block text-gray-700 text-sm font-bold mb-2">Sexo:</label>
                    <select id="sex" name="sex"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Selecione</option>
                        <option value="MALE" {{ old('sex', $person->getSex()) == 'MALE' ? 'selected' : '' }}>Masculino</option>
                        <option value="FEMALE" {{ old('sex', $person->getSex()) == 'FEMALE' ? 'selected' : '' }}>Feminino</option>
                        <option value="OTHER" {{ old('sex', $person->getSex()) == 'OTHER' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                {{-- Estado Civil --}}
                <div>
                    <label for="marital_status" class="block text-gray-700 text-sm font-bold mb-2">Estado Civil:</label>
                    <select id="marital_status" name="marital_status"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Selecione</option>
                        <option value="single" {{ old('marital_status', $person->getMaritalStatus()) == 'single' ? 'selected' : '' }}>Solteiro(a)</option>
                        <option value="married" {{ old('marital_status', $person->getMaritalStatus()) == 'married' ? 'selected' : '' }}>Casado(a)</option>
                        <option value="divorced" {{ old('marital_status', $person->getMaritalStatus()) == 'divorced' ? 'selected' : '' }}>Divorciado(a)</option>
                        <option value="widow" {{ old('marital_status', $person->getMaritalStatus()) == 'widow' ? 'selected' : '' }}>Viúvo(a)</option>
                        <option value="stable union" {{ old('marital_status', $person->getMaritalStatus()) == 'stable union' ? 'selected' : '' }}>União Estável</option>
                    </select>
                </div>

                {{-- Departamento da Empresa --}}
                <div>
                    <label for="company_dept" class="block text-gray-700 text-sm font-bold mb-2">Departamento da Empresa:</label>
                    <input type="text" id="company_dept" name="company_dept" value="{{ old('company_dept', $person->getCompanyDept()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Cargo --}}
                <div>
                    <label for="job_position" class="block text-gray-700 text-sm font-bold mb-2">Cargo:</label>
                    <input type="text" id="job_position" name="job_position" value="{{ old('job_position', $person->getJobPosition()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Endereço --}}
                <div class="md:col-span-2">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Endereço:</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $person->getAddress()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Complemento --}}
                <div>
                    <label for="complement" class="block text-gray-700 text-sm font-bold mb-2">Complemento:</label>
                    <input type="text" id="complement" name="complement" value="{{ old('complement', $person->getComplement()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- CEP --}}
                <div>
                    <label for="cep" class="block text-gray-700 text-sm font-bold mb-2">CEP:</label>
                    <input type="text" id="cep" name="cep" value="{{ old('cep', $person->getCep()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Bairro --}}
                <div>
                    <label for="neighborhood" class="block text-gray-700 text-sm font-bold mb-2">Bairro:</label>
                    <input type="text" id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $person->getNeighborhood()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Cidade --}}
                <div>
                    <label for="city" class="block text-gray-700 text-sm font-bold mb-2">Cidade:</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $person->getCity()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Estado --}}
                <div>
                    <label for="state" class="block text-gray-700 text-sm font-bold mb-2">Estado (UF):</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $person->getState()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- País --}}
                <div>
                    <label for="country" class="block text-gray-700 text-sm font-bold mb-2">País:</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $person->getCountry()) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>

            <div class="flex items-center justify-between mt-8">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out shadow-md">
                    Salvar Alterações
                </button>
                <a href="#" onclick="history.back()" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>

       // =============================================================
        // CÓDIGO PARA EL FORMATO DE FECHAS dd/mm/yyyy
        // =============================================================
        const birthdatePicker = document.getElementById('birthdate_picker');
        const birthdateDisplay = document.getElementById('birthdate_display');

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
        if (birthdatePicker && birthdateDisplay) {
            birthdatePicker.addEventListener('change', function() {
                const selectedDate = this.value; // El valor del input type="date" siempre es YYYY-MM-DD
                birthdateDisplay.textContent = formatDateForDisplay(selectedDate);
            });
        }

        // Si ya hay un valor de `old('expected_closing_date')` al cargar la página,
        // el Blade ya lo habrá formateado inicialmente. Pero si se interactúa con
        // el campo sin un `old` value, el JS lo manejará.
        // Asegurarse de que el span muestre el valor inicial si existe.
        if (birthdatePicker.value && birthdateDisplay.textContent.trim() === '') {
             birthdateDisplay.textContent = formatDateForDisplay(birthdatePicker.value);
        }
        
 


</script>
@endpush
