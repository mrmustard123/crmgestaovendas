@extends('layouts.app')

@section('page_title', 'Minhas Oportunidades')

@push('styles')
    <style>
        .kanban-board {
            display: flex;
            gap: 1.5rem; /* Espacio entre columnas */
            overflow-x: auto;
            padding-bottom: 1rem;
            align-items: flex-start;
        }
        .kanban-column {
            flex-shrink: 0;
            width: 320px; /* Ancho fijo para las columnas */
            background-color: #f0f2f5;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        .kanban-column-header {
            font-weight: bold;
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
            color: #333;
            text-align: center;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #ccc;
        }
        .kanban-cards {
            flex-grow: 1; /* Para que ocupe el espacio restante */
            min-height: 100px; /* Permite arrastrar elementos */
            padding-top: 0.5rem;
        }
        .kanban-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            cursor: grab;
            display: flex; /* Para el icono y el nombre */
            align-items: center;
            justify-content: space-between; /* Icono a la derecha */
        }
        .kanban-card:active {
            cursor: grabbing;
        }
        .kanban-card.dragging {
            opacity: 0.5;
            border: 2px dashed #007bff;
        }
        .card-emoji {
            font-size: 1.5rem; /* Tamaño del emoji */
            margin-right: 0.5rem;
        }
        /* Estilos para las columnas adicionales */
        .action-column {
            width: 150px; /* Ancho más pequeño para columnas de acción */
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centrar contenido verticalmente */
        }
        .action-column-header {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #555;
        }
        .action-button {
            background-color: #007bff;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            display: block;
        }
        .action-button:hover {
            background-color: #0056b3;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Minhas Oportunidades</h2>
        
            {{-- Definir las etapas (stages) estáticamente, según tu seeder --}}
            @php
                $stages = [
                    1 => ['id'=>1, 'name' => 'Apresentação', 'color' => '#007bff'],
                    2 => ['id'=>2, 'name' => 'Proposta', 'color' => '#007bff'],
                    3 => ['id'=>3, 'name' => 'Negociação', 'color' => '#007bff'],
                    4 => ['id'=>4, 'name' => 'Ganho/Perdido', 'color' => '#007bff'],
                ];
            @endphp        

        <div class="kanban-board">
            {{-- Columna "Nome de la Opportunity" (No interactiva, solo lista) --}}
            <div class="kanban-column">
                <div class="kanban-column-header">Nome da Oportunidade</div>
                <div class="kanban-cards">
                    @foreach ($opportunities as $opportunity)
                        <div class="kanban-card border-none shadow-none bg-transparent cursor-default">
                            <span class="font-semibold text-gray-700">{{ $opportunity['opportunityName'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Columnas de Etapas (Arrastrables) --}}
            @foreach ($stages as $stage)
                <div class="kanban-column" data-stage-id="{{ $stage['id'] }}">
                    <div class="kanban-column-header">{{ $stage['name'] }}</div>
                    <div class="kanban-cards">
                        @forelse ($opportunities as $opportunity)
                            @if (  isset($opportunity['stageId']) && ($opportunity['stageId'] == $stage['id'])  )
                                <div class="kanban-card" draggable="true" data-opportunity-id="{{ $opportunity['opportunityId'] }}">                                    
                                    {{-- Aquí puedes usar un emoji o un ícono --}}
                                    <span class="card-emoji">&#x1F4B0;</span> {{-- Emoji de billetes --}}
                                </div>
                            @endif
                        @empty
                            {{-- No hay oportunidades en esta etapa --}}
                        @endforelse
                    </div>
                </div>
            @endforeach

            {{-- Columna "Nova Atividade" --}}
            <div class="action-column">
                <div class="action-column-header">Nova Atividade</div>
                <a href="" class="action-button">
                    &#x270F;&#xFE0F; Nova Atividade
                </a>
            </div>

            {{-- Columna "Documentos" --}}
            <div class="action-column">
                <div class="action-column-header">Documentos</div>
                <a href="#" class="action-button" onclick="alert('Funcionalidade de Documentos será implementada.'); return false;">
                    &#x1F4C4; Documentos
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const draggables = document.querySelectorAll('.kanban-card[draggable="true"]');
            const columns = document.querySelectorAll('.kanban-column[data-stage-id]'); // Solo columnas de etapas

            draggables.forEach(card => {
                card.addEventListener('dragstart', () => {
                    card.classList.add('dragging');
                });

                card.addEventListener('dragend', () => {
                    card.classList.remove('dragging');
                });
            });

            columns.forEach(column => {
                column.addEventListener('dragover', e => {
                    e.preventDefault(); // Permite el drop
                    const afterElement = getDragAfterElement(column, e.clientY);
                    const draggable = document.querySelector('.dragging');
                    if (draggable) {
                        if (afterElement == null) {
                            column.querySelector('.kanban-cards').appendChild(draggable);
                        } else {
                            column.querySelector('.kanban-cards').insertBefore(draggable, afterElement);
                        }
                    }
                });

                column.addEventListener('drop', e => {
                    e.preventDefault();
                    const draggable = document.querySelector('.dragging');
                    if (draggable) {
                        const opportunityId = draggable.dataset.opportunityId;
                        const newStageId = column.dataset.stageId;

                        // Solo procede si la etapa cambió (opcional, para evitar llamadas innecesarias)
                        const currentStageId = draggable.closest('.kanban-column').dataset.stageId;
                        if (currentStageId !== newStageId) {
                            updateOpportunityStage(opportunityId, newStageId);
                        }
                    }
                });
            });

            function getDragAfterElement(column, y) {
                const draggableCards = [...column.querySelectorAll('.kanban-card:not(.dragging)')];

                return draggableCards.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
            }

            function updateOpportunityStage(opportunityId, newStageId) {
                console.log(`Moviendo Oportunidad ID: ${opportunityId} a Etapa ID: ${newStageId}`);

                fetch(`/opportunities/${opportunityId}/update-stage`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Protección CSRF de Laravel
                    },
                    body: JSON.stringify({ stage_id: newStageId })
                })
                .then(response => {
                    if (!response.ok) {
                        // Intentar leer el cuerpo del error si no es una respuesta OK
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Error al actualizar la etapa.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Etapa actualizada con éxito:', data);
                    // Opcional: mostrar un mensaje de éxito al usuario
                    alert('Etapa de la oportunidad actualizada correctamente!');
                    // Recargar la página o actualizar solo el elemento afectado si es necesario
                    // location.reload();
                })
                .catch(error => {
                    console.error('Error al actualizar la etapa:', error);
                    alert('Hubo un error al mover la oportunidad: ' + error.message);
                    // Opcional: revertir el movimiento visual si hay un error
                    // location.reload(); // Recargar para reflejar el estado correcto si hubo un fallo
                });
            }
        });
    </script>
@endpush