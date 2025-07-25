<?php
/*
Author: Leonardo G. Tellez Saucedo
Email: leonardo616@gmail.com
*/
?>
@extends('layouts.app')

@section('page_title', 'Historico de Opportunidades')    
    
@section('content') 
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            Informações do Vendedor: <span class="text-blue-600">{{ $vendor->getVendorName() }}</span>
        </h1>


        {{-- Seção de Outras Oportunidades --}}
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Outras Oportunidades do Vendedor</h2>
        @if (count($vendorOpportunities) > 0)
            <div class="overflow-x-auto mb-10">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left rounded-tl-lg">Nome da Oportunidade</th>
                            <th class="py-3 px-6 text-left">Data de Abertura</th>
                            <th class="py-3 px-6 text-left">Status da Oportunidade</th>
                            <th class="py-3 px-6 text-left rounded-tr-lg">Pessoa Associada</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @foreach ($vendorOpportunities as $opportunity)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">

                                    
                                <td  class="py-3 px-6 text-left whitespace-nowrap">
                                    <a href="{{ route('salesperson.opportunities.edit', ['id' => $opportunity['opportunity_id']]) }}">{{ $opportunity['opportunity_name'] }}</a>                                
                                </td>                                    
                                    

                                <td class="py-3 px-6 text-left">
                                    <span>{{ $opportunity['open_date'] }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $opportunity['opportunity_status'] }}</span>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $opportunity['associated_person'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 text-lg mt-8 mb-10">Nenhuma outra oportunidade encontrada para este vendedor.</p>
        @endif

        <div class="mt-8 text-center">
            <a href="#" onclick="history.back()" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
                Voltar
            </a>
        </div>
    </div>
@endsection
