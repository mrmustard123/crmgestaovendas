@extends('layouts.app')

@section('page_title', 'Minhas Oportunidades')

@push('styles')
<style>
       /* Contenedor principal */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1px; /* Para que el borde redondeado sea visible */
        }
        
        /* Estilos de la tabla */
        .kanban-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95rem;
            min-width: 1000px; /* Ancho mínimo para evitar compresión */
        }
        
        /* Encabezados */
        .kanban-table th {
            background-color: #2c3e50;
            color: white;
            padding: 15px 12px;
            text-align: center;
            font-weight: 600;
            border: 1px solid #1a252f;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        /* Celdas */
        .kanban-table td {
            padding: 12px;
            vertical-align: middle;
            border: 1px solid #e0e0e0;
            background-color: white;
            transition: background-color 0.2s;
        }
        
        /* Efecto hover para filas */
        .kanban-table tbody tr:hover td {
            background-color: #f8f9fa;
        }
        
        /* Columnas específicas */
        .name-column {
            width: 300px;
            font-weight: 500;
            color: #34495e;
            border-left: 2px solid #e0e0e0;
        }
        
        .stage-column {
            width: 200px;
            text-align: center;
            background-color: #f8fafc;
        }
        
        .action-column {
            width: 180px;
            text-align: center;
            background-color: #f1f8fe;
            border-right: 2px solid #e0e0e0;
        }
        
        /* Bordes especiales para primera y última columna */
        .kanban-table td:first-child {
            border-left: 2px solid #e0e0e0;
        }
        
        .kanban-table td:last-child {
            border-right: 2px solid #e0e0e0;
        }
        
        /* Tarjeta arrastrable */
        .kanban-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            cursor: grab;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            width: 50px;
            height: 50px;
        }
        
        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        }
        
        .kanban-card:active {
            cursor: grabbing;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .kanban-card.dragging {
            opacity: 0.7;
            border: 2px dashed #3498db;
            background-color: #ebf5fb;
        }
        
        .card-emoji {
            font-size: 1.8rem;
        }
        
        /* Botones de acción */
        .action-button {
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            width: 140px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .action-button:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Efecto al arrastrar sobre columnas */
        .stage-column.drag-over {
            background-color: #ebf5fb;
            box-shadow: inset 0 0 0 2px #3498db;
        }
        
        /* Pie de tabla visual */
        .kanban-table tbody tr:last-child td {
            border-bottom: 2px solid #e0e0e0;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .kanban-table th, 
            .kanban-table td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
            
            .kanban-card {
                width: 40px;
                height: 40px;
                padding: 8px;
            }
            
            .card-emoji {
                font-size: 1.5rem;
            }
            
            .action-button {
                width: 120px;
                padding: 6px 10px;
            }
        }
    /* Nuevos estilos para botones Ganho/Perdido */
    .lost-column, .won-column {
        width: 200px;
        text-align: center;
        background-color: #f8fafc;
    }

    .lost-button {
        background-color: #ff4444; /* Rojo para Perdido */
        color: white;
        border: 1px solid #cc0000;
    }

    .won-button {
        background-color: #00C851; /* Verde para Ganho */
        color: white;
        border: 1px solid #007E33;
    }

    .lost-button, .won-button {
        padding: 12px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        width: 50px;
        height: 50px;
        margin: 0 auto;
    }

    .lost-button:hover {
        background-color: #cc0000;
        transform: translateY(-2px);
    }

    .won-button:hover {
        background-color: #007E33;
        transform: translateY(-2px);
    }

    /* Deshabilitar drag and drop en estas columnas */
    .lost-column, .won-column {
        pointer-events: none; /* Deshabilita eventos en la celda */
    }
    
    .lost-button, .won-button {
        pointer-events: auto; /* Pero habilita en los botones */
    }
</style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Minhas Oportunidades (Em Aberto)</h2>
        
        @php
            $stages = [
                1 => ['id' => 1, 'name' => 'Apresentação', 'color' => '#007bff'],
                2 => ['id' => 2, 'name' => 'Proposta', 'color' => '#007bff'],
                3 => ['id' => 3, 'name' => 'Negociação', 'color' => '#007bff'],                
            ];
        @endphp

        <div class="table-container">
            <table class="kanban-table">
                <thead>
                    <tr>
                        <th class="name-column">Nome da Oportunidade</th>
                        @foreach ($stages as $stage)
                            <th class="stage-column">{{ $stage['name'] }}</th>
                        @endforeach
                        <th class="lost-column">Perdido</th>
                        <th class="won-column">Ganho</th>                        
                        <th class="action-column">Atividades</th>
                        <th class="action-column">Documentos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($opportunities as $stageHistory)
                        @php
                            $opportunity = $stageHistory->getOpportunity();
                            $currentStage = $stageHistory->getStage();
                        @endphp
                        
                        <tr data-opportunity-id="{{ $opportunity->getOpportunityId() }}">
                            <td class="name-column">
                                <a href="{{ route('salesperson.opportunities.edit', ['id' => $opportunity->getOpportunityId()]) }}">{{ $opportunity->getOpportunityName() }}</a>                                
                            </td>
                            
                            @foreach ($stages as $stage)
                                <td class="stage-column" data-stage-id="{{ $stage['id'] }}">
                                    @if ($currentStage->getStageId() == $stage['id'])
                                        <div class="kanban-card" 
                                             draggable="true"
                                             data-opportunity-id="{{ $opportunity->getOpportunityId() }}"
                                             data-stage-history-id="{{ $stageHistory->getStageHistId() }}">
                                            <span class="card-emoji">&#x1F4B0;</span>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                            
                            <td class="lost-column">
                                <button class="lost-button" 
                                        data-opportunity-id="{{ $opportunity->getOpportunityId() }}"
                                        data-stage-history-id="{{ $stageHistory->getStageHistId() }}">
                                    &#128577; <!-- Emoji triste -->
                                </button>
                            </td>
                            
                            <td class="won-column">
                                <button class="won-button" 
                                        data-opportunity-id="{{ $opportunity->getOpportunityId() }}"
                                        data-stage-history-id="{{ $stageHistory->getStageHistId() }}">
                                    &#127942; <!-- Emoji trofeo -->
                                </button>
                            </td>
                            
                            <td class="action-column">  
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('salesperson.opportunities.activities.create', ['opportunityId' => $opportunity->getOpportunityId()]) }}" class="action-button">
                                        &#x270F;&#xFE0F; Nova Atividade
                                    </a>
                                </div>
                            </td>
                            <td class="action-column">
                                <div class="flex flex-col gap-2">
                                     <a href="{{ route('salesperson.opportunities.documents.upload', ['opportunityId' => $opportunity->getOpportunityId()]) }}" class="action-button">
                                        &#x1F4C4; Documentos
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Configuración inicial
        const draggables = document.querySelectorAll('.kanban-card[draggable="true"]');
        const stageColumns = document.querySelectorAll('td.stage-column[data-stage-id]');
        const wonButtons = document.querySelectorAll('.won-button');
        const lostButtons = document.querySelectorAll('.lost-button');       
        
        
        // Manejo del arrastre de tarjetas
        draggables.forEach(card => {
            card.addEventListener('dragstart', (e) => {
                card.classList.add('dragging');
                e.dataTransfer.setData('opportunity-id', card.dataset.opportunityId);
                e.dataTransfer.setData('stage-history-id', card.dataset.stageHistoryId);
                e.dataTransfer.effectAllowed = 'move';
                
                setTimeout(() => {
                    card.style.visibility = 'hidden';
                }, 0);
            });
            
            card.addEventListener('dragend', () => {
                document.querySelectorAll('.stage-column').forEach(col => {
                    col.classList.remove('drag-over');
                });
                card.classList.remove('dragging');
                card.style.visibility = 'visible';
            });
        });
        
        // Manejo de las columnas de destino
        stageColumns.forEach(column => {
            column.addEventListener('dragenter', (e) => {
                e.preventDefault();
                column.classList.add('drag-over');
            });
            
            column.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            
            column.addEventListener('dragleave', () => {
                column.classList.remove('drag-over');
            });
            
            column.addEventListener('drop', async (e) => {
                e.preventDefault();
                column.classList.remove('drag-over');
                
                const opportunityId = e.dataTransfer.getData('opportunity-id');
                const stageHistoryId = e.dataTransfer.getData('stage-history-id');
                const newStageId = column.dataset.stageId;
                const card = document.querySelector(`.kanban-card[data-opportunity-id="${opportunityId}"]`);
                
                if (!card) return;
                
                try {
                    const response = await fetch(`{{ url('/') }}/salesperson/opportunities/${opportunityId}/update-stage`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            stage_id: newStageId,
                            stage_history_id: stageHistoryId 
                        })
                    });
                    
                    if (!response.ok) {
                        throw new Error(await response.text());
                    }
                    
                    const data = await response.json();
                    console.log('Etapa atualizada:', data);
                    
                    // Recargar la página para reflejar los cambios
                    window.location.reload();
                    
                } catch (error) {
                    console.error('Error:', error);
                    alert('Erro ao atualizar a etapa: ' + error.message);
                }
            });
        });
 /*       
        // Manejo de los botones Ganho/Perdido
        wonButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const opportunityId = button.dataset.opportunityId;
                
                if (confirm('Tem certeza que deseja marcar esta oportunidade como GANHO?')) {
                    await updateOpportunityStatus(opportunityId, 'won');
                }
            });
        });
        
        lostButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const opportunityId = button.dataset.opportunityId;
                
                if (confirm('Tem certeza que deseja marcar esta oportunidade como PERDIDO?')) {
                    await updateOpportunityStatus(opportunityId, 'lost');
                }
            });
        });
*/
/*        
        async function updateOpportunityStatus(opportunityId, status) {
            try {
                const response = await fetch(`{{ url('/') }}/salesperson/opportunities/${opportunityId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                });
                
                if (!response.ok) {
                    throw new Error(await response.text());
                }
                
                const data = await response.json();
                console.log('Status atualizado:', data);
                window.location.reload();
                
            } catch (error) {
                console.error('Error:', error);
                alert('Erro ao atualizar o status: ' + error.message);
            }
        }
 */       
        
        /********* Manejo de los botones Ganho/Perdido*************/
        
        wonButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const opportunityId = button.dataset.opportunityId;
                const stageHistoryId = button.dataset.stageHistoryId;
                
                if (confirm('Tem certeza que deseja marcar esta oportunidade como GANHO?')) {
                    try {
                        const response = await fetch(`{{ url('/') }}/salesperson/opportunities/${opportunityId}/mark-as-won`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                stage_history_id: stageHistoryId 
                            })
                        });
                        
                        if (!response.ok) {
                            throw new Error(await response.text());
                        }
                        
                        const data = await response.json();
                        console.log('Oportunidade marcada como ganha:', data);
                        
                        // Actualizar visualmente el botón
                        button.style.backgroundColor = '#007E33';
                        button.innerHTML = '&#x2714;'; // Emoji checkmark
                        
                        // Recargar después de 1 segundo para ver cambios
                        setTimeout(() => window.location.reload(), 1000);
                        
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Erro ao marcar como ganho: ' + error.message);
                    }
                }
            });
        });
        
        lostButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const opportunityId = button.dataset.opportunityId;
                const stageHistoryId = button.dataset.stageHistoryId;
                
                if (confirm('Tem certeza que deseja marcar esta oportunidade como PERDIDO?')) {
                    try {
                        const response = await fetch(`{{ url('/') }}/salesperson/opportunities/${opportunityId}/mark-as-lost`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ 
                                stage_history_id: stageHistoryId 
                            })
                        });
                        
                        if (!response.ok) {
                            throw new Error(await response.text());
                        }
                        
                        const data = await response.json();
                        console.log('Oportunidade marcada como perdida:', data);
                        
                        // Actualizar visualmente el botón
                        button.style.backgroundColor = '#cc0000';
                        button.innerHTML = '&#x2716;'; // Emoji X
                        
                        // Recargar después de 1 segundo para ver cambios
                        setTimeout(() => window.location.reload(), 1000);
                        
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Erro ao marcar como perdido: ' + error.message);
                    }
                }
            });
        });        
        
        
        
        
        
        
    });
</script>
@endpush