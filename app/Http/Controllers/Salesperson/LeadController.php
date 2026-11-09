<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
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
use App\Models\Doctrine\ProductService;
use App\Models\Doctrine\ProdServOpp;
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
        $productServices = $this->entityManager->getRepository(ProductService::class)->findAll();

        return view('salesperson.leads.create', compact('leadOrigins', 'productServices'));
    }

    /**
     * Almacena un nuevo Lead, y automáticamente crea una Oportunidad y una Actividad inicial.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        
        
            // --- INICIO DE MANEJO DE FORMATO DE NÚMEROS BRASILEÑO ---
            // El campo 'estimated_sale' vienen como string con coma decimal.
            // Lo transformamos a float con punto decimal para PHP y la DB.
            if ($request->has('estimated_sale') && $request->estimated_sale !== null && $request->estimated_sale !== '') {
                $request->merge([
                    'estimated_sale' => (float) str_replace(',', '.', str_replace('.', '', $request->estimated_sale))
                ]);
            }

            // --- FIN DE MANEJO DE FORMATO DE NÚMEROS BRASILEÑO ---        
        
        
        
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

            // Validación condicional para Company existente o nueva
            'is_company_representative' => 'boolean', // checkbox
            'company_id' => 'nullable|integer|exists:App\Models\Doctrine\Company,company_id', // Para vincular a una compañía existente            
            'social_reason' => [
                 Rule::requiredIf($request->boolean('is_company_representative') && empty($request->input('company_id'))),
                'nullable', 'string', 'max:200'
            ],
            // Los demás campos de compañía solo son requeridos si se va a crear una nueva compañía
            'fantasy_name' => 'nullable|string|max:200',            
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
            $person = null;            
            // Paso 1: Determinar la Compañía (existente o nueva)                             
            // 1. Crear o vincular Company si es un representante
            $is_company_representative = $request->boolean('is_company_representative');
            if ($is_company_representative) {                                
                if ($request->filled('company_id')) {
                    // Buscar compañía existente
                    $company = $this->entityManager->getRepository(Company::class)->find($request->input('company_id'));
                    if (!$company) {
                        throw new \Exception("Companhia com ID " . $request->input('company_id') . " não encontrada.");
                    }
                    // Opcional: Podría actualizar datos de la compañía existente aquí, pero el requerimiento es solo relacionarla.
                } else {
                    // Crear nueva Compañía                      
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
            }
            
            // Paso 2: Determinar la Persona (Lead existente o nueva)
            $personRoleLead = $this->entityManager->getRepository(PersonRole::class)->findOneBy(['role_name' => 'Lead']);
            if (!$personRoleLead) {
                throw new \Exception("PersonRole 'Lead' não encontrado. Por favor, verifique se os seeders foram executados.");
            }      
            
            
            if ($request->filled('person_id')) {
                // Buscar persona existente
                $person = $this->entityManager->getRepository(Person::class)->find($request->input('person_id'));
                if (!$person) {
                    throw new \Exception("Pessoa com ID " . $request->input('person_id') . " não encontrada.");
                }
                /*/ Asegurar que la persona existente sea un Lead
                if ($person->getPersonRole()->getRoleName() !== 'Lead') {
                    // Opcional: Lanzar error o cambiar el rol si es necesario
                    // Por ahora, lanzamos error para mantener la consistencia del flujo de "Lead"
                    throw new \Exception("A pessoa existente (ID: " . $request->input('person_id') . ") não tem o rol de 'Lead'.");
                }*/
                // Si la persona existente no tiene compañía, y se proporcionó una, la vinculamos.
                if ($company && !$person->getCompany()) {
                    $person->setCompany($company);
                    $this->entityManager->persist($person); // persistir cambios en la persona
                    $this->entityManager->flush();
                }
            } else {                                    
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
            }
            

            // 3. Crear Opportunity
            $opportunityStatusOpened = $this->entityManager->getRepository(OpportunityStatus::class)->findOneBy(['status' => 'Aberto']);
            if (!$opportunityStatusOpened) {
                throw new \Exception("OpportunityStatus 'Aberto' não encontrado. Por favor, verifique se os seeders foram executados.");
            }

            $currentAuthUser = Auth::user();
            if (!$currentAuthUser) {
                throw new \Exception("Nenhum usuário autenticado encontrado.");
            }

            $vendor = $this->entityManager->getRepository(Vendor::class)->findOneBy(['user' => $currentAuthUser]);
            if (!$vendor) {
                throw new \Exception("O usuário autenticado não está vinculado a um vendedor.");
            }

            $opportunity = new Opportunity();
            $opportunity->setOpportunityName($request->input('opportunity_name'));
            $opportunity->setDescription($request->input('description'));
            $opportunity->setEstimatedSale($request->input('estimated_sale'));
            $opportunity->setCurrency($request->input('currency'));
            $opportunity->setOpenDate(now());
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
            
            $stageRepository = $this->entityManager->getRepository(Stage::class);
            $firstStage = $stageRepository->findOneBy([], ['stage_id' => 'ASC']);           

            $stageHistory = new StageHistory($opportunity, $stageApresentacao); // Constructor de StageHistory
            $stageHistory->setStageHistDate(new DateTime());
            if ($firstStage) {
                $stageHistory->setStage($firstStage);
            } 
            $stageHistory->setOpportunity($opportunity);                        
            $stageHistory->setComments("Oportunidade criada, estágio inicial 'Apresentação'.");
            //$stageHistory->set
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
            
            
            // ======================================================================
            // PROCESAR PRODUCTOS/SERVICIOS SELECCIONADOS
            // ======================================================================
            $selectedProductServiceIds = $request->input('selected_product_services', []);

            if (!empty($selectedProductServiceIds)) {
                foreach ($selectedProductServiceIds as $productServiceId) {
                    // Buscar la entidad ProductService por su ID
                    $productService = $this->entityManager->getRepository(ProductService::class)->find((int)$productServiceId);

                    if ($productService) {
                        // Crear una nueva entrada en la tabla ProdServOpp
                        $prodServOpp = new ProdServOpp($opportunity, $productService);
                        $this->entityManager->persist($prodServOpp);
                    } else {
                        // Opcional: Loguear si un ID enviado no existe, aunque la validación previa debería prevenir esto
                        Log::warning("ProductService con ID {$productServiceId} não encontrado ao criar ProdServOpp para a Oportunidade {$opportunity->getOpportunityId()}");
                    }
                }
            }
            // ======================================================================

            // Guardar todos los cambios en la base de datos dentro de la transacción
            $this->entityManager->flush();
                  
            //GRABAR TODOS LOS DATOS
            $this->entityManager->commit();

            return redirect()->route('salesperson.leads.create')->with('success', 'Lead, Oportunidade e Atividade inicial registrados com sucesso!');

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            // Log the error for debugging
            \Log::error("Error registering lead and opportunity: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro ao registrar o Lead e Oportunidade: ' . $e->getMessage());
        }
    }
    
    
  /**
     * Busca personas por nombre para el autocompletado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPerson(Request $request)
    {
        // Obtiene el término de búsqueda del parámetro 'q'
        $query = $request->query('q'); 

        // Validar que la cadena de búsqueda no esté vacía y tenga al menos 3 caracteres
        if (empty($query) || strlen($query) < 3) {
            return response()->json([]); // Devuelve un array vacío si no hay suficientes caracteres
        }

        $personRepository = $this->entityManager->getRepository(Person::class);

        // Construye la consulta DQL (Doctrine Query Language)
        $persons = $personRepository->createQueryBuilder('p')
            ->where('p.person_name LIKE :query') // Busca por nombre de persona
            // ->orWhere('p.main_email LIKE :query') // Opcional: Descomentar si también quieres buscar por email
            // ->orWhere('p.main_phone LIKE :query') // Opcional: Descomentar si también quieres buscar por teléfono
            ->setParameter('query', '%' . $query . '%') // El comodín % permite buscar coincidencias parciales
            ->setMaxResults(10) // Limita el número de resultados para no sobrecargar
            ->getQuery()
            ->getResult();
        
        


        $results = [];
        foreach ($persons as $person) {
            $companyData = null;
            $company = $person->getCompany(); // Asume que Person tiene un método getCompany()
            if ($company) {
                $companyData = [
                    'id' => $company->getCompanyId(),
                    'social_reason' => $company->getSocialReason(),
                    'fantasy_name' => $company->getFantasyName(),
                    'cnpj' => $company->getCnpj(),
                    'inscricao_estadual' => $company->getInscricaoEstadual(),
                    'inscricao_municipal' => $company->getInscricaoMunicipal(),
                    'main_phone' => $company->getMainPhone(),    
                    'main_email' => $company->getMainEmail(),
                    'address' => $company->getAddress(),
                    'complement' => $company->getComplement(),
                    'neighborhood' => $company->getNeighborhood(),
                    'city' => $company->getCity(),
                    'state' => $company->getState(),
                    /*'zip_code' => $company->getZipCode(), <--NO EXISTE*/
                    'country' => $company->getCountry(),                   
                    'status' => $company->getStatus(),
                    'comments' => $company->getComments(),
                ];
            }
       
            
            $results[] = [
                'id' => $person->getPersonId(),
                'name' => $person->getPersonName(),
                'main_phone' => $person->getMainPhone(),
                'main_email' => $person->getMainEmail(),
                'address' => $person->getAddress(),
                'complement' => $person->getComplement(),                
                'neighborhood' => $person->getNeighborhood(),
                'city' => $person->getCity(),
                'state' => $person->getState(),
                'country' => $person->getCountry(),                
                'person_role_id' => $person->getPersonRole() ? $person->getPersonRole()->getPersonRoleId() : null,
                'company' => $companyData ? $companyData : null, // Incluye todos los datos de la compañía asociada
            ];
        }

        return response()->json($results);
    }    
    
    
}