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
    
    <!-- Sección 1: Pipeline Temporal -->
    <div class="mb-10">
        <h4 class="text-md font-semibold text-gray-800 mb-4">Pipeline por Período</h4>
        
        @if($pipelineData->isEmpty())
            <p class="text-gray-500">Nenhuma oportunidade encontrada para o período selecionado.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($pipelineData as $item)
                <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Mês</p>
                    <p class="font-bold text-lg">{{ $item->month }}</p>
                    <div class="mt-2">
                        <p class="text-gray-500 text-sm">Valor Total</p>
                        <p class="text-xl font-semibold">R$ {{ number_format($item->total_value, 2, ',', '.') }}</p>
                    </div>
                    <div class="mt-2">
                        <p class="text-gray-500 text-sm">Oportunidades</p>
                        <p class="text-lg">{{ $item->opportunity_count }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Sección 2: Análisis por Etapa -->
    <div>
        <h4 class="text-md font-semibold text-gray-800 mb-4">Análise por Etapa</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($stageAnalysis as $stage)
            <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                <p class="text-gray-500 text-sm">Etapa</p>
                <p class="font-bold text-lg">{{ $stage['stage_name'] }}</p>
                
                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Probabilidade de Fechamento</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $stage['probability'] }}%</p>
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