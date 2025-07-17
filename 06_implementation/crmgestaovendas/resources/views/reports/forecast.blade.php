@extends('layouts.app')

@section('page_title', 'Previsão de Vendas')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-6">
        Previsão de Vendas
    </h3>
    
    <!-- Selector de período -->
    <div class="mb-8">
        <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Período de Projeção</label>
        <select id="period" onchange="window.location.href='?period='+this.value" 
                class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="3" {{ $selectedPeriod == 3 ? 'selected' : '' }}>Próximos 3 meses</option>
            <option value="6" {{ $selectedPeriod == 6 ? 'selected' : '' }}>Próximos 6 meses</option>
            <option value="12" {{ $selectedPeriod == 12 ? 'selected' : '' }}>Próximos 12 meses</option>
        </select>
    </div>
    
    <!-- Sección 1: Pipeline Temporal (Tabla Elegante) -->
    <div class="mb-10">
        <h4 class="text-md font-semibold text-gray-800 mb-4">Pipeline por Período</h4>
        
        @if(empty($pipelineData))
            <p class="text-gray-500">Nenhuma oportunidade encontrada para o período selecionado.</p>
        @else
            <div class="overflow-x-auto">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden rounded-lg border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mês
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Valor Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Oportunidades
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Valor Médio
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pipelineData as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $item['month'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-900 font-semibold">R$ {{ number_format($item['total_value'], 2, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $item['opportunity_count'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    R$ {{ number_format($item['total_value'] / $item['opportunity_count'], 2, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                            @if(count($pipelineData) > 1)
                            <tr class="bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                    R$ {{ number_format(array_sum(array_column($pipelineData, 'total_value')), 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                    {{ array_sum(array_column($pipelineData, 'opportunity_count')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    R$ {{ number_format(array_sum(array_column($pipelineData, 'total_value')) / array_sum(array_column($pipelineData, 'opportunity_count')), 2, ',', '.') }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Sección 2: Análisis por Etapa (Tarjetas) -->
    <div>
        <h4 class="text-md font-semibold text-gray-800 mb-4">Análise por Etapa</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($stageAnalysis as $stage)
            <div class="border-l-4 border-gray-500 rounded shadow p-5"
                 style="background-color: {{ $stage['color_hex'] }}">
                <p class="text-gray-500 text-sm">Etapa</p>
                <p class="font-bold text-lg">{{ $stage['stage_name'] }}</p>
                
                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Probabilidade de Fechamento</p>
                    <p class="text-lg">{{ $stage['probability'] }}%</p>
                </div>
                
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Valor Total</p>
                        <p class="text-lg font-semibold">R$ {{ number_format($stage['total_value'], 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Oportunidades</p>
                        <p class="text-lg">{{ $stage['opportunity_count'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection