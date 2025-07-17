@extends('layouts.app')

@section('page_title', 'Subir Documento para Oportunidad #' . $opportunity->getOpportunityId())

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Subir Documento para Oportunidad: <span class="text-blue-600">{{ $opportunity->getOpportunityName() }}</span></h2>

        {{-- Formulario para subir un nuevo documento --}}
        {{-- ¡Importante! 'enctype="multipart/form-data"' es crucial para que los archivos se envíen --}}
        <form action="{{ route('salesperson.opportunities.documents.store', ['opportunityId' => $opportunity->getOpportunityId()]) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <div class="mb-4">
                <label for="document_file" class="block text-gray-700 text-sm font-bold mb-2">Seleccionar Archivo:</label>
                <input type="file" name="document_file" id="document_file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('document_file') border-red-500 @enderror" required>
                @error('document_file')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
                <p class="text-gray-600 text-xs mt-1">Formatos permitidos: PDF, Word (doc, docx), Imágenes (JPG, JPEG, PNG). Tamaño máximo: 5MB.</p>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción (Opcional):</label>
                <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Subir Documento
                </button>
                <a href="{{ route('salesperson.myopportunities') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Cancelar
                </a>
            </div>
        </form>

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
    </div>
@endsection