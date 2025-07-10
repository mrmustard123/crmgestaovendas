<?php
/*
File: myopportunities.blade.php
Author: Leonardo Tellez
Purpose: Display a list of current vendor's opportunities.
*/
?>

@extends('layouts.app')

@section('page_title', 'Minhas Oportunidades')

@push('styles')
    <style>
        .kanban-board {
            display: flex;
            gap: 1.5rem; /* Aumentado el espacio entre columnas */
            overflow-x: auto;
            padding-bottom: 1rem;
            align-items: flex-start; /* Asegura que las columnas se alineen en la parte superior */
        }
        .kanban-column {
            flex-shrink: 0;
            width: 320px; /* Ancho fijo para las columnas */
            background-color: #f0f2f5;
            border-radius: 8px;
            padding: 1.5rem; /* Aumentado el padding */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra más pronunciada */
            min-height: 400px; /* Para que las columnas tengan altura y se pueda soltar */
            display: flex;
            flex-direction: column;
        }
        .kanban-column-header {
            font-weight: bold;
            font-size: 1.25rem; /* Título más grande */
            margin-bottom: 1.25rem; /* Espacio debajo del título */
            color: #333;
            text-align: center;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #ccc;
        }
        .kanban-cards {
            flex-grow: 1;
            min-height: 50px; /* Altura mínima para arrastrar si no hay tarjetas */
        }
        .kanban-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px; /* Bordes más redondeados */
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08); /* Sombra más suave para las tarjetas */
            cursor: grab; /* Indica que el elemento es arrastrable */
            transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
        .kanban-card:hover {
            background-color: #f9f9f9;
            transform: translateY(-2px); /* Pequeño efecto al pasar el mouse */
        }
        .kanban-card.dragging {
            opacity: 0.5; /* Opacidad reducida durante el arrastre */
            border: 2px dashed #007bff;
        }
        .kanban-column.drag-over {
            background-color: #e0e6ea; /* Color de fondo al arrastrar sobre la columna */
        }
        .kanban-card p {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #555;
        }
        .kanban-card strong {
            color: #333;
        }
        .kanban-card .opportunity-name {
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        .kanban-card .expected-date {
            font-size: 0.85rem;
            color: #777;
            text-align: right;
            margin-top: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Gestão de Oportunidades - Kanban</h1>
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
        
        
        
        
        <div class="kanban-board">
            {{-- Definir las etapas (stages) estáticamente, según tu seeder --}}
            @php
                $stages = [
                    1 => ['name' => 'Apresentação', 'color' => '#007bff'],
                    2 => ['name' => 'Proposta', 'color' => '#007bff'],
                    3 => ['name' => 'Negociação', 'color' => '#007bff'],
                    4 => ['name' => 'Ganho/Perdido', 'color' => '#007bff'],
                ];
            @endphp

            @foreach ($stages as $stageId => $stageData)
                <div class="kanban-column" data-stage-id="{{ $stageId }}" style="border-top: 4px solid {{ $stageData['color'] }};">
                    <div class="kanban-column-header">
                        {{ $stageData['name'] }}
                    </div>
                    <div class="kanban-cards">
                        @forelse ($opportunities as $opportunity)                        
                        
                        
                        <?php
                       /*    if (config('app.debug')) {
                            xdebug_break(); 
                           }
                          
                               
                         $stageHistory = $opportunities[0]->getStageHistory();                              
                            
                        echo $stageHistory->getStageHistory();
                        echo $stageHistory->getStage();
                        echo $stageHistory->getStageId();    
                            
                        
                       echo ''; */
                        ?>
                        
                        

                            <div class="kanban-card" draggable="true" data-opportunity-id="{{ $opportunity['opportunityId'] }}">
                                <div class="opportunity-name">{{ $opportunity['opportunityName'] }}</div>

                            </div>                       
                                                
                        
                        @empty
                            <p class="text-sm text-gray-500 text-center">Nenhuma oportunidade nesta etapa.</p>
                        @endforelse                                                
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
            const draggables = document.querySelectorAll('.kanban-card');
            const columns = document.querySelectorAll('.kanban-column');

            draggables.forEach(card => {
                card.addEventListener('dragstart', dragStart);
                card.addEventListener('dragend', dragEnd);
            });

            columns.forEach(column => {
                column.addEventListener('dragover', dragOver);
                column.addEventListener('dragleave', dragLeave);
                column.addEventListener('drop', drop);
            });

            let draggedCard = null;

            function dragStart() {
                draggedCard = this;
                setTimeout(() => this.classList.add('dragging'), 0);
            }

            function dragEnd() {
                this.classList.remove('dragging');
                draggedCard = null;
            }

            function dragOver(e) {
                e.preventDefault(); // Permite el drop
                const column = this;
                if (column.contains(draggedCard) && draggedCard !== null) {
                    // Si el elemento arrastrado ya está en esta columna, no hacemos nada especial
                    return;
                }
                column.classList.add('drag-over');
            }

            function dragLeave() {
                this.classList.remove('drag-over');
            }

            function drop() {
                this.classList.remove('drag-over');
                if (draggedCard) {
                    const newStageId = this.dataset.stageId;
                    const opportunityId = draggedCard.dataset.opportunityId;
                    const currentStageId = draggedCard.closest('.kanban-column').dataset.stageId;

                    // Si la tarjeta ya está en la columna, no hacemos nada
                    if (newStageId === currentStageId) {
                        return;
                    }

                    // Mueve visualmente la tarjeta
                    this.querySelector('.kanban-cards').appendChild(draggedCard);

                    // Llama a una función para actualizar la base de datos
                    updateOpportunityStage(opportunityId, newStageId);
                }
            }

            function updateOpportunityStage(opportunityId, newStageId) {
                // Aquí deberías realizar una llamada AJAX a tu backend
                // para actualizar el fk_stage en la tabla stage_history.
                console.log(`Actualizando Oportunidad ${opportunityId} a Etapa ${newStageId}`);

                // Ejemplo con fetch API (requiere un endpoint en Laravel)
                fetch(`{{ url('/') }}/opportunities/${opportunityId}/update-stage`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Protección CSRF de Laravel
                    },
                    body: JSON.stringify({ stage_id: newStageId })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Error al actualizar la etapa.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Etapa actualizada con éxito:', data);
                    // Opcional: mostrar un mensaje de éxito al usuario
                })
                .catch(error => {
                    console.error('Error al actualizar la etapa:', error);
                    alert('Hubo un error al mover la oportunidad: ' + error.message);
                    // Opcional: revertir el movimiento visual si hay un error
                    // location.reload(); // Recargar la página para reflejar el estado correcto
                });
            }
        });
    </script>
@endpush