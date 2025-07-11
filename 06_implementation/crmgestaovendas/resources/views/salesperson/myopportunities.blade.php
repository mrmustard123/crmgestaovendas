<?php $__env->startSection('page_title', 'Minhas Oportunidades'); ?>

<?php $__env->startPush('styles'); ?>
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
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Minhas Oportunidades</h2>
        
        <?php
                /*JUST FOR TESTING */    
                    if (config('app.debug')) {
                        xdebug_break();
                    }
                
                    foreach ($opportunities as $stageHistory) {
                        $opportunity_name = $stageHistory->getOpportunity()->getOpportunityName();
                        $opportunity_id = $stageHistory->getOpportunity->getOpportunity();                    
                        $stage_id = $stageHistory->getStage()->getStage();
                    }        
               /********************/
        
        ?>
        
                
        <?php
            $stages = [
                1 => ['id' => 1, 'name' => 'Apresentação', 'color' => '#007bff'],
                2 => ['id' => 2, 'name' => 'Proposta', 'color' => '#007bff'],
                3 => ['id' => 3, 'name' => 'Negociação', 'color' => '#007bff'],
            ];
        ?>

        <div class="table-container">
            <table class="kanban-table">
                <thead>
                    <tr>
                        <th class="name-column">Nome da Oportunidade</th>
                        <?php 
                            $__currentLoopData = $stages; 
                            $__env->addLoop($__currentLoopData); 
                            foreach($__currentLoopData as $stage): 
                                $__env->incrementLoopIndices(); 
                                $loop = $__env->getLastLoop(); 
                        ?>
                                <th class="stage-column"><?php echo e($stage['name']); ?></th>
                       <?php 
                            endforeach; 
                            $__env->popLoop(); 
                            $loop = $__env->getLastLoop(); 
                        ?>
                        <th class="action-column">Nova Atividade</th>
                        <th class="action-column">Documentos</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); 
                    foreach($__currentLoopData as $opportunity): 
                        $__env->incrementLoopIndices(); 
                        $loop = $__env->getLastLoop(); 
                ?>
                        <tr data-opportunity-id="<?php echo e($opportunity['opportunityId']); ?>">
                            <td class="name-column">
                                <?php echo e($opportunity['opportunityName']); ?>

                            </td>
                            
                            <?php 
                                $__currentLoopData = $stages; 
                                $__env->addLoop($__currentLoopData); 
                                foreach($__currentLoopData as $stage): 
                                    $__env->incrementLoopIndices(); 
                                    $loop = $__env->getLastLoop(); 
                            ?>
                                <td class="stage-column" data-stage-id="<?php echo e($stage['id']); ?>">
                                   <?php 
                                        if($opportunity['stageId'] == $stage['id']): ?>
                                        <div class="kanban-card" 
                                             draggable="true"
                                             data-opportunity-id="<?php echo e($opportunity['opportunityId']); ?>">
                                            <span class="card-emoji">&#x1F4B0;</span>
                                        </div>
                                   <?php 
                                        endif; 
                                   ?>
                                </td>
                            <?php 
                                endforeach; 
                                $__env->popLoop(); 
                                $loop = $__env->getLastLoop(); 
                            ?>
                                
                            <td class="action-column">
                                <a href="#" class="action-button">
                                    &#x270F;&#xFE0F; Perdido
                                </a>
                            </td>                                
                                
                            <td class="action-column">
                                <a href="#" class="action-button">
                                    &#x270F;&#xFE0F; Ganho
                                </a>
                            </td>                                
                            
                            <td class="action-column">
                                <a href="#" class="action-button">
                                    &#x270F;&#xFE0F; Nova Atividade
                                </a>
                            </td>
                            <td class="action-column">
                                <a href="#" class="action-button" 
                                   onclick="alert('Funcionalidade de Documentos será implementada.'); return false;">
                                    &#x1F4C4; Documentos
                                </a>
                            </td>
                        </tr>
               <?php 
                    endforeach; 
                    $__env->popLoop(); 
                    $loop = $__env->getLastLoop(); 
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const draggables = document.querySelectorAll('.kanban-card[draggable="true"]');
        const stageColumns = document.querySelectorAll('td.stage-column[data-stage-id]');
        
        draggables.forEach(card => {
            card.addEventListener('dragstart', (e) => {
                card.classList.add('dragging');
                e.dataTransfer.setData('text/plain', card.dataset.opportunityId);
                e.dataTransfer.effectAllowed = 'move';
                
                // Efecto visual durante el arrastre
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
                
                const opportunityId = e.dataTransfer.getData('text/plain');
                const newStageId = column.dataset.stageId;
                const card = document.querySelector(`.kanban-card[data-opportunity-id="${opportunityId}"]`);
                
                if (!card) return;
                
                // Limpiar todas las columnas de etapa para esta oportunidad
                document.querySelectorAll(`td.stage-column[data-opportunity-id="${opportunityId}"]`)
                    .forEach(td => td.innerHTML = '');
                
                // Mover visualmente la tarjeta
                column.innerHTML = '';
                column.appendChild(card);
                
                try {
                    const response = await fetch(`<?php echo e(url('/')); ?>/salesperson/opportunities/${opportunityId}/update-stage`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ stage_id: newStageId })
                    });
                    
                    if (!response.ok) {
                        throw new Error(await response.text());
                    }
                    
                    const data = await response.json();
                    console.log('Estagio atualizado:', data);
                    
                    // Actualizar el atributo para futuros arrastres
                    card.setAttribute('data-current-stage', newStageId);
                    
                } catch (error) {
                    console.error('Error:', error);
                    alert('Erro ao atualizar o estgaio: ' + error.message);
                    // Podrías agregar aquí la lógica para revertir visualmente el cambio
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
