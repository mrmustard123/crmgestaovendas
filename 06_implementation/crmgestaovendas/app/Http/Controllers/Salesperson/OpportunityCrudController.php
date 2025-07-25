<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Opportunity;
use App\Models\Doctrine\OpportunityStatus;
use App\Models\Doctrine\LeadOrigin;
use App\Models\Doctrine\ProdServOpp;
use App\Models\Doctrine\ProductService;
use App\Models\Doctrine\Stage; 
use App\Models\Doctrine\StageHistory;
use App\Models\Doctrine\Activity;
use App\Models\Doctrine\Document;
use App\Models\Doctrine\Person; 
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
        // Usar QueryBuilder para eager load las relaciones necesarias
        $opportunity = $this->entityManager->getRepository(Opportunity::class)
                                         ->createQueryBuilder('o')
                                         ->addSelect('os', 'lo', 'a', 'd','pso', 'ps') // Seleccionar estados, origen, prod/serv, etapa, persona, actividades, documentos
                                         ->leftJoin('o.fk_op_status_id', 'os')
                                         ->leftJoin('o.lead_origin_id', 'lo')
                                         // Asegúrate de que ProdServOpp se carga correctamente si lo necesitas en la vista
                                         // Si ProdServOpp es una entidad intermedia, necesitarás manejarla de otra forma
                                         // Por ahora, solo cargamos actividades y documentos directamente.
                                         ->leftJoin('o.activities', 'a') // Carga ansiosa de actividades
                                         ->leftJoin('o.documents', 'd')   // Carga ansiosa de documentos
                                          ->leftJoin('o.prodServOpps', 'pso') // <-- UNE a la colección ProdServOpp
                                          ->leftJoin('pso.productService', 'ps') // <-- Luego UNE al ProductService a través de ProdServOpp                
                                         ->where('o.opportunity_id = :id')
                                         ->setParameter('id', $id)
                                         ->getQuery()
                                         ->getOneOrNullResult();

        if (!$opportunity) {
            abort(404, 'Oportunidade não encontrada.');
        }

        // Obtener todos los datos para los selects del formulario principal
        $leadOrigins = $this->entityManager->getRepository(LeadOrigin::class)->findAll();
        $opportunityStatuses = $this->entityManager->getRepository(OpportunityStatus::class)->findAll();


        // Si necesitas ProdServOpp para mostrar en el formulario principal, puedes cargarlas así:
//        $prodServOpps = $this->entityManager->getRepository(ProdServOpp::class)
//                                           ->findBy(['opportunity' => $opportunity->getOpportunityId()]);

        return view('salesperson.edit', [
            'opportunity' => $opportunity,
            'leadOrigins' => $leadOrigins,
            'opportunityStatuses' => $opportunityStatuses,
            'prodServOpps' => $opportunity->getProdServOpps(),
            // Las actividades y documentos ya están en el objeto $opportunity gracias al eager loading
        ]);
    }
    

    /**
     * Convierte formato numérico brasilero a formato estándar
     * Ejemplo: "1.234.567,89" -> "1234567.89"
     */
    private function convertBrazilianToStandardFormat($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Convertir a string si no lo es
        $stringValue = (string) $value;

        // Si no contiene separadores, asumir que ya está en formato estándar
        if (!str_contains($stringValue, ',') && !str_contains($stringValue, '.')) {
            return $stringValue;
        }

        // Si contiene coma (formato brasilero)
        if (str_contains($stringValue, ',')) {
            // Remover puntos (separadores de miles) y reemplazar coma por punto decimal
            $converted = str_replace('.', '', $stringValue);
            $converted = str_replace(',', '.', $converted);
            return $converted;
        }

        // Si solo contiene puntos, determinar si es separador de miles o decimal
        $dotCount = substr_count($stringValue, '.');
        if ($dotCount > 1) {
            // Múltiples puntos: todos menos el último son separadores de miles
            $lastDotPos = strrpos($stringValue, '.');
            $integerPart = substr($stringValue, 0, $lastDotPos);
            $decimalPart = substr($stringValue, $lastDotPos + 1);

            // Si la parte decimal tiene más de 2 dígitos, probablemente no es decimal
            if (strlen($decimalPart) > 2) {
                // Tratar todos los puntos como separadores de miles
                return str_replace('.', '', $stringValue);
            } else {
                // El último punto es decimal
                return str_replace('.', '', $integerPart) . '.' . $decimalPart;
            }
        }

        // Un solo punto: podría ser decimal o separador de miles
        // Si tiene más de 2 dígitos después del punto, probablemente es separador de miles
        $parts = explode('.', $stringValue);
        if (count($parts) === 2 && strlen($parts[1]) > 2) {
            return str_replace('.', '', $stringValue);
        }

        // Asumir formato estándar
        return $stringValue;
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
        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($id);
        if (!$opportunity) {
            return redirect()->back()->with('error', 'Oportunidade não encontrada.');
        }
        
        // Procesar el campo estimated_sale para convertir formato brasilero a estándar
        $estimatedSale = $request->input('estimated_sale');
        if ($estimatedSale) {
            // Convertir formato brasilero (1.234,56) a formato estándar (1234.56)
            $estimatedSale = $this->convertBrazilianToStandardFormat($estimatedSale);
            $request->merge(['estimated_sale' => $estimatedSale]);
        }    
        
        // Procesar el campo estimated_sale para convertir formato brasilero a estándar
        $final_price = $request->input('final_price');
        if ($final_price) {
            // Convertir formato brasilero (1.234,56) a formato estándar (1234.56)
            $final_price = $this->convertBrazilianToStandardFormat($final_price);
            $request->merge(['final_price' => $final_price]);
        }         

        $validatedData = $request->validate([
            'opportunity_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'estimated_sale' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
            'expected_closing_date' => 'nullable|date',
            'currency' => 'required|string|max:3',
            'lead_origin_id' => 'nullable|integer',
            'fk_op_status_id' => 'required|integer',
            'priority' => 'required|string|in:Low,Medium,High,Critical',
            'fk_person' => 'nullable|integer', // Puede ser null
            'open_date' => 'required|date',
        ]);

        $opportunity->setOpportunityName($validatedData['opportunity_name']);
        $opportunity->setDescription($validatedData['description']);
        $opportunity->setEstimatedSale((float) $validatedData['estimated_sale']);
        $opportunity->setFinalPrice((float) $validatedData['final_price']);
        $opportunity->setExpectedClosingDate($validatedData['expected_closing_date'] ? new DateTime($validatedData['expected_closing_date']) : null);
        $opportunity->setCurrency($validatedData['currency']);

        // Actualizar LeadOrigin
        if (!empty($validatedData['lead_origin_id'])) {
            $leadOrigin = $this->entityManager->getRepository(LeadOrigin::class)->find($validatedData['lead_origin_id']);
            $opportunity->setLeadOrigin($leadOrigin);
        } else {
            $opportunity->setLeadOrigin(null);
        }

        // Actualizar OpportunityStatus
        $opportunityStatus = $this->entityManager->getRepository(OpportunityStatus::class)->find($validatedData['fk_op_status_id']);
        $opportunity->setOpportunityStatus($opportunityStatus);

        $opportunity->setPriority($validatedData['priority']);

        $opportunity->setOpenDate(new DateTime($validatedData['open_date']));

        $this->entityManager->persist($opportunity);
        $this->entityManager->flush();

        return redirect()->route('salesperson.opportunities.edit', ['id' => $opportunity->getOpportunityId()])
                         ->with('success', 'Oportunidade atualizada com sucesso.');
    }
}
