<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Person;
use App\Models\Doctrine\Company;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\LeadOrigin;
use App\Models\Doctrine\PersonRole;
use App\Models\Doctrine\OpportunityStatus;
use App\Models\Doctrine\Stage;
use App\Models\Doctrine\StageHistory;
use App\Models\Doctrine\Activity;
use App\Models\Doctrine\Vendor; // Para obtener el vendedor asociado al usuario
use DateTime;
use DateTimeImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Para reglas de validación condicionales

class LeadController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para registrar un nuevo Lead.
     * Incluye datos para Person y Company (opcional), Opportunity y primera Activity.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $leadOrigins = $this->entityManager->getRepository(LeadOrigin::class)->findAll();
        // Opcional: Puedes pasar PersonRoles si quieres que el vendedor vea los tipos,
        // pero por la lógica, el rol inicial siempre es 'Lead'.

        return view('salesperson.leads.create', compact('leadOrigins'));
    }

    /**
     * Almacena un nuevo Lead, y automáticamente crea una Oportunidad y una Actividad inicial.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validación para Person (Lead)
            'person_name' => 'required|string|max:255',
            'main_phone' => 'nullable|string|max:20',
            'main_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2', // UF
            'country' => 'required|string|max:100',
            'job_position' => 'nullable|string|max:100',
            'lead_origin_id' => 'required|integer|exists:' . LeadOrigin::class . ',lead_origin_id',

            // Validación condicional para Company
            'is_company_representative' => 'boolean', // checkbox
            'social_reason' => [
                Rule::requiredIf($request->boolean('is_company_representative')),
                'nullable', 'string', 'max:200'
            ],
            'fantasy_name' => 'nullable|string|max:200',
            'cnpj' => 'nullable|string|max:18',
            'inscricao_estadual' => 'nullable|string|max:20',
            'inscricao_municipal' => 'nullable|string|max:20',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string|max:255',
            'company_complement' => 'nullable|string|max:100',
            'company_neighborhood' => 'nullable|string|max:100',
            'company_city' => 'nullable|string|max:100',
            'company_state' => 'nullable|string|max:2',
            'company_country' => [
                Rule::requiredIf($request->boolean('is_company_representative')),
                'nullable', 'string', 'max:100'
            ],
            'company_comments' => 'nullable|string',

            // Validación para Opportunity
            'opportunity_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'estimated_sale' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'expected_closing_date' => 'nullable|date',
        ]);

        $this->entityManager->beginTransaction();

        try {
            $company = null;
            $personRoleLead = $this->entityManager->getRepository(PersonRole::class)->findOneBy(['role_name' => 'Lead']);
            if (!$personRoleLead) {
                throw new \Exception("PersonRole 'Lead' not found. Please ensure seeders are run.");
            }

            // 1. Crear o vincular Company si es un representante
            if ($request->boolean('is_company_representative')) {
                $company = new Company();
                $company->setSocialReason($request->input('social_reason'));
                $company->setFantasyName($request->input('fantasy_name'));
                $company->setCnpj($request->input('cnpj'));
                $company->setInscricaoEstadual($request->input('inscricao_estadual'));
                $company->setInscricaoMunicipal($request->input('inscricao_municipal'));
                $company->setMainPhone($request->input('company_phone'));
                $company->setMainEmail($request->input('company_email'));
                $company->setAddress($request->input('company_address'));
                $company->setComplement($request->input('company_complement'));
                $company->setNeighborhood($request->input('company_neighborhood'));
                $company->setCity($request->input('company_city'));
                $company->setState($request->input('company_state'));
                $company->setCountry($request->input('company_country'));
                $company->setComments($request->input('company_comments'));
                $company->setStatus('unactive'); // Estado inicial como 'unactive'
                $this->entityManager->persist($company);
                $this->entityManager->flush(); // Flush para obtener el ID de la compañía
            }

            // 2. Crear Person (Lead)
            $person = new Person();
            $person->setPersonName($request->input('person_name'));
            $person->setMainPhone($request->input('main_phone'));
            $person->setMainEmail($request->input('main_email'));
            $person->setAddress($request->input('address'));
            $person->setComplement($request->input('complement'));
            $person->setNeighborhood($request->input('neighborhood'));
            $person->setCity($request->input('city'));
            $person->setState($request->input('state'));
            $person->setCountry($request->input('country'));
            $person->setJobPosition($request->input('job_position'));
            $person->setPersonRole($personRoleLead); // Asignar rol 'Lead'            

            if ($company) {
                $person->setCompany($company); // Vincular a la compañía si existe
            }
            $this->entityManager->persist($person);
            $this->entityManager->flush(); // Flush para obtener el ID de la persona

            // 3. Crear Opportunity
            $opportunityStatusOpened = $this->entityManager->getRepository(OpportunityStatus::class)->findOneBy(['status' => 'Aberto']);
            if (!$opportunityStatusOpened) {
                throw new \Exception("OpportunityStatus 'Aberto' not found. Please ensure seeders are run.");
            }

            $currentAuthUser = Auth::user();
            if (!$currentAuthUser) {
                throw new \Exception("No authenticated user found.");
            }

            $vendor = $this->entityManager->getRepository(Vendor::class)->findOneBy(['user' => $currentAuthUser]);
            if (!$vendor) {
                throw new \Exception("Authenticated user is not linked to a Vendor.");
            }

            $opportunity = new Opportunity();
            $opportunity->setOpportunityName($request->input('opportunity_name'));
            $opportunity->setDescription($request->input('description'));
            $opportunity->setEstimatedSale($request->input('estimated_sale'));
            $opportunity->setCurrency($request->input('currency'));
            $opportunity->setExpectedClosingDate($request->input('expected_closing_date') ? new DateTime($request->input('expected_closing_date')) : null);
            $opportunity->setOpportunityStatus($opportunityStatusOpened); // Estado inicial 'Aberto'
            $opportunity->setPerson($person); // Vincular a la Persona
            $opportunity->setVendor($vendor); // Vincular al Vendedor
            $opportunity->setLeadOrigin($this->entityManager->getRepository(LeadOrigin::class)->find($request->input('lead_origin_id')));            
            $this->entityManager->persist($opportunity);
            $this->entityManager->flush(); // Flush para obtener el ID de la oportunidad

            // 4. Crear StageHistory inicial (etapa 0: Apresentação)
            $stageApresentacao = $this->entityManager->getRepository(Stage::class)->findOneBy(['stage_order' => 0]);
            if (!$stageApresentacao) {
                throw new \Exception("Stage 'Apresentação' (order 0) not found. Please ensure seeders are run.");
            }

            $stageHistory = new StageHistory($opportunity, $stageApresentacao); // Constructor de StageHistory
            $stageHistory->setStageHistDate(new DateTime());
            $stageHistory->setComments("Oportunidade criada, estágio inicial 'Apresentação'.");
            // won_lost es null al inicio
            $this->entityManager->persist($stageHistory);
            $this->entityManager->flush(); // Flush para asegurar que StageHistory se guarde

            // 5. Crear Activity inicial
            $activity = new Activity();
            $activity->setTitulo("Contato inicial com o Lead: " . $person->getPersonName());
            $activity->setDescription("Primeiro contato com o Lead para apresentar os produtos/serviços.");
            $activity->setActivityDate(new DateTime()); // Fecha actual
            $activity->setStatus('scheduled'); // Programada por defecto
            $activity->setOpportunity($opportunity); // Vincular a la oportunidad            
            $this->entityManager->persist($activity);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return redirect()->route('salesperson.leads.create')->with('success', 'Lead, Oportunidade e Atividade inicial registrados com sucesso!');

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            // Log the error for debugging
            \Log::error("Error registering lead and opportunity: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro ao registrar o Lead e Oportunidade: ' . $e->getMessage());
        }
    }
}