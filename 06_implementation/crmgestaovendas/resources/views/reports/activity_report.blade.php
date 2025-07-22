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
                        <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Data Inicial:</label>
                        <input type="date" id="start_date" name="start_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror"
                            value="{{ old('start_date', $startDate ? $startDate->format('Y-m-d') : '') }}">
                        @error('start_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Data Final:</label>
                        <input type="date" id="end_date" name="end_date"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror"
                            value="{{ old('end_date', $endDate ? $endDate->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
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
