<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
?>
@extends('layouts.app')

@section('page_title', 'Meus Leads')    
    
@section('content')    
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            Leads do Vendedor: <span class="text-blue-600">{{ $vendor->getVendorName() }}</span>
        </h1>

        @if (count($leads) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">                            
                            <th class="py-3 px-6 text-left">Nome do Lead</th>
                            <th class="py-3 px-6 text-left">Email do Lead</th>
                            <th class="py-3 px-6 text-left">Telefone do Lead</th>
                            <th class="py-3 px-6 text-left">Prioridade</th>
                            <th class="py-3 px-6 text-left">Preço Estimado</th>
                            <th class="py-3 px-6 text-left rounded-tr-lg">Data de Fechamento Esperada</th>
                            <th class="py-3 px-6 text-left rounded-tl-lg">Nome da Oportunidade</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($leads as $lead)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">
                                    <a href="{{ route('salesperson.persons.edit', ['personId' => $lead['person_id']]) }}" class="text-blue-500 hover:text-blue-700 font-semibold">
                                        {{ $lead['person_name'] }}
                                    </a>                                                                        
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $lead['main_email'] ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $lead['main_phone'] ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="py-1 px-3 rounded-full text-xs
                                        @if($lead['priority'] == 'High' || $lead['priority'] == 'Critical') bg-red-200 text-red-600
                                        @elseif($lead['priority'] == 'Medium') bg-yellow-200 text-yellow-600
                                        @else bg-green-200 text-green-600 @endif">
                                        {{ $lead['priority'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>R$ {{ number_format($lead['estimated_sale'], 2, ',', '.') }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $lead['expected_closing_date'] }}</span>
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $lead['opportunity_name'] }}</span>
                                    </div>
                                </td>                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 text-lg mt-8">Nenhum Lead encontrado para este vendedor.</p>
        @endif

        <div class="mt-8 text-center">
            <a href="#" onclick="history.back()" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
                Voltar
            </a>
        </div>
    </div>

@endsection