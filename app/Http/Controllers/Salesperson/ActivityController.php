<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Activity; // Importa tu entidad Activity
use App\Models\Doctrine\Opportunity; // Importa tu entidad Opportunity
use DateTime; // Para manejar la fecha de la actividad

class ActivityController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para crear una nueva actividad.
     *
     * @param int $opportunityId El ID de la oportunidad a la que se asociará la actividad.
     * @return \Illuminate\View\View
     */
    public function create(int $opportunityId)
    {
        // Recuperar la oportunidad para asegurar que existe y pasarla a la vista
        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($opportunityId);

        if (!$opportunity) {
            abort(404, 'Oportunidad no encontrada.');
        }

        // Puedes pasar opciones para los select, si los tienes (ej. status, result)
        $activityStatuses = ['scheduled' => 'Agendado', 'performed' => 'Concluído', 'canceled' => 'Cancelado', 'resheduled' => 'Reagendado'];
        $activityResults = ['positive' => 'Positivo', 'negative' => 'Negativo', 'neutral' => 'Neutro', '' => 'Sem resultado']; // Añadir opción vacía para 'null'
        $activityActivityTypes = ['Call' => 'Ligação', 'Meeting' => 'Reunião', 'Email' => 'Email'];
        
        return view('salesperson.activities.create', [
            'opportunity' => $opportunity,
            'activityStatuses' => $activityStatuses,
            'activityResults' => $activityResults,
            'activityActivityTypes' => $activityActivityTypes,
        ]);
    }

    /**
     * Almacena una nueva actividad en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $opportunityId El ID de la oportunidad a la que se asociará la actividad.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $opportunityId)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:200',
            'description' => 'nullable|string',
            'activity_date' => 'required|date',
            'duration_min' => 'nullable|integer|min:1',
            'status' => 'required|in:scheduled,performed,canceled,resheduled',
            'result' => 'nullable|in:positive,negative,neutral',
            'activity_type' => 'nullable|in:Call,Meeting,Email',
            'comments' => 'nullable|string',
        ]);

        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($opportunityId);

        if (!$opportunity) {
            return redirect()->back()->with('error', 'Oportunidade não encontrada.');
        }

        $activity = new Activity(); //
        $activity->setTitulo($validatedData['titulo']); //
        $activity->setDescription($validatedData['description']); //
        $activity->setActivityDate(new DateTime($validatedData['activity_date'])); //
        $activity->setDurationMin($validatedData['duration_min']); //
        $activity->setStatus($validatedData['status']); //
        $activity->setResult($validatedData['result']); //
        $activity->setActivityType($validatedData['activity_type']);
        $activity->setComments($validatedData['comments']); //
        $activity->setOpportunity($opportunity); // Asocia la actividad a la oportunidad

        $this->entityManager->persist($activity);
        $this->entityManager->flush();

        // Redirigir a algún lugar después de guardar, quizás a la página de detalles de la oportunidad
        // O de vuelta a la lista de oportunidades
        return redirect()->route('salesperson.myopportunities')->with('success', 'Atividade registrada com sucesso.');
    }
}