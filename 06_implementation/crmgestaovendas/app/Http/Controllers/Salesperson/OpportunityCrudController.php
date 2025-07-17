<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\OpportunityStatus;
use App\Models\Doctrine\LeadOrigin;
use App\Models\Doctrine\ProdServOpp;
use App\Models\Doctrine\Stage; 
use App\Models\Doctrine\StageHistory;
use DateTime; // Importar DateTime para setExpectedClosingDate y setOpenDate
use DateTimeImmutable; // Importar DateTimeImmutable

class OpportunityCrudController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para editar una oportunidad.
     *
     * @param int $id El ID de la oportunidad a editar.
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);
        $opportunity = $opportunityRepository->find($id);

        if (!$opportunity) {
            abort(404, 'Oportunidad no encontrada.');
        }

        // Obtener todos los LeadOrigins para el select
        $leadOrigins = $this->entityManager->getRepository(LeadOrigin::class)->findAll();

        // Obtener todos los OpportunityStatus para el select
        $opportunityStatuses = $this->entityManager->getRepository(OpportunityStatus::class)->findAll();

        // Obtener los productos/servicios asociados (solo para mostrar)
        $prodServOpps = $this->entityManager->getRepository(ProdServOpp::class)->findBy(['opportunity' => $opportunity]);

        // Obtener la última StageHistory asociada (solo para mostrar la etapa actual)
        // Se busca la historia de la etapa más reciente para esta oportunidad.
        $latestStageHistory = $this->entityManager->getRepository(StageHistory::class)
            ->findOneBy(
                ['fk_opportunity' => $opportunity],
                ['stage_hist_date' => 'DESC', 'stage_hist_id' => 'DESC'] // Ordenar por fecha y luego por ID para el más reciente
            );
        $currentStage = $latestStageHistory ? $latestStageHistory->getStage() : null;

        return view('salesperson.edit', [
            'opportunity' => $opportunity,
            'leadOrigins' => $leadOrigins,
            'opportunityStatuses' => $opportunityStatuses,
            'prodServOpps' => $prodServOpps,
            'currentStage' => $currentStage,
        ]);
    }

    /**
     * Actualiza una oportunidad en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id El ID de la oportunidad a actualizar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $opportunityRepository = $this->entityManager->getRepository(Opportunity::class);
        $opportunity = $opportunityRepository->find($id);

        if (!$opportunity) {
            return redirect()->back()->with('error', 'Oportunidad no encontrada.');
        }

        // Validar los datos de entrada
        $validatedData = $request->validate([
            'opportunity_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'estimated_sale' => 'required|numeric|min:0',
            'expected_closing_date' => 'nullable|date',
            'currency' => 'nullable|string|max:3',
            'lead_origin_id' => 'nullable|exists:' . LeadOrigin::class . ',lead_origin_id',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'fk_op_status_id' => 'nullable|exists:' . OpportunityStatus::class . ',opportunity_status_id',
        ]);

        // Actualizar los campos que son editables
        $opportunity->setOpportunityName($validatedData['opportunity_name']);
        $opportunity->setDescription($validatedData['description']);
        $opportunity->setEstimatedSale((float) $validatedData['estimated_sale']);

        // Fechas pueden ser null
        $opportunity->setExpectedClosingDate($validatedData['expected_closing_date'] ? new DateTime($validatedData['expected_closing_date']) : null);
        $opportunity->setCurrency($validatedData['currency']);

        // Actualizar LeadOrigin
        if (!empty($validatedData['lead_origin_id'])) {
            $leadOrigin = $this->entityManager->getRepository(LeadOrigin::class)->find($validatedData['lead_origin_id']);
            $opportunity->setLeadOrigin($leadOrigin);
        } else {
            $opportunity->setLeadOrigin(null);
        }

        // Actualizar Priority
        $opportunity->setPriority($validatedData['priority']);

        // Actualizar OpportunityStatus
        if (!empty($validatedData['fk_op_status_id'])) {
            $opportunityStatus = $this->entityManager->getRepository(OpportunityStatus::class)->find($validatedData['fk_op_status_id']);
            $opportunity->setOpportunityStatus($opportunityStatus);
        } else {
            $opportunity->setOpportunityStatus(null);
        }

        // Doctrine se encarga de updated_at gracias a #[ORM\PreUpdate]
        $this->entityManager->persist($opportunity);
        $this->entityManager->flush();

        return redirect()->route('salesperson.opportunities.edit', ['id' => $opportunity->getOpportunityId()])
                         ->with('success', 'Oportunidad actualizada exitosamente.');
    }
}
