<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Company; // Asegúrate de importar tu entidad Company


class CompanyController extends Controller
{
private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Busca compañías por nombre social o nombre de fantasía.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCompany(Request $request)
    {
        $query = $request->query('q');

        if (!$query || strlen($query) < 3) {
            return response()->json([]);
        }

        $repository = $this->entityManager->getRepository(Company::class);

        // Usar Doctrine's QueryBuilder para buscar por social_reason o fantasy_name
        $qb = $repository->createQueryBuilder('c');

        $companies = $qb
            ->where($qb->expr()->like('c.social_reason', ':query'))
            ->orWhere($qb->expr()->like('c.fantasy_name', ':query'))
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10) // Limitar resultados para eficiencia
            ->getQuery()
            ->getResult();

        $results = [];
        foreach ($companies as $company) {
            $results[] = [
                'id' => $company->getCompanyId(),
                'social_reason' => $company->getSocialReason(),
                'fantasy_name' => $company->getFantasyName(),
                'cnpj' => $company->getCnpj(),
                'inscricao_estadual' => $company->getInscricaoEstadual(),
                'inscricao_municipal' => $company->getInscricaoMunicipal(),
                'cep' => $company->getCep(),
                'main_phone' => $company->getMainPhone(),
                'main_email' => $company->getMainEmail(),
                'website' => $company->getWebsite(),
                'address' => $company->getAddress(),
                'complement' => $company->getComplement(),
                'neighborhood' => $company->getNeighborhood(), 
                'city' => $company->getCity(),
                'state' => $company->getState(),
                'country' => $company->getCountry(),
                'status' => $company->getStatus(),
                'comments' => $company->getComments(),
            ];
        }

        return response()->json($results);
    }
}
