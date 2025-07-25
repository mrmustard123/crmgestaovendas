<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers\salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctrine\Person;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DateTime; // Para campos de data

class PersonController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor para injetar o EntityManager.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe o formulário para editar os dados de uma Pessoa (Lead).
     *
     * @param int $personId O ID da Pessoa.
     * @return View
     */
    public function edit(int $personId): View
    {               
        
        // Obtém o repositório da entidade Person
        $personRepository = $this->entityManager->getRepository(Person::class);
        // Busca a Pessoa pelo ID
        $person = $personRepository->find($personId);

        // Se a Pessoa não for encontrada, aborta com erro 404
        if (!$person) {
            abort(404, 'Lead não encontrado.');
        }

        // Retorna a vista 'edit_person' passando o objeto Person
        return view('salesperson.edit_person', [
            'person' => $person,
        ]);
    }

    /**
     * Atualiza os dados de uma Pessoa (Lead) no banco de dados.
     *
     * @param Request $request A requisição HTTP contendo os dados do formulário.
     * @param int $personId O ID da Pessoa a ser atualizada.
     * @return RedirectResponse
     */
    public function update(Request $request, int $personId): RedirectResponse
    {
        // Obtém o repositório da entidade Person
        $personRepository = $this->entityManager->getRepository(Person::class);
        // Busca a Pessoa pelo ID
        $person = $personRepository->find($personId);

        // Se a Pessoa não for encontrada, redireciona de volta com erro
        if (!$person) {
            return redirect()->back()->with('error', 'Lead não encontrado para atualização.');
        }

        // Validação dos dados do formulário
        $request->validate([
            'person_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'complement' => 'nullable|string|max:100',
            'main_phone' => 'nullable|string|max:20',
            'main_email' => 'nullable|email|max:255',
            'rg' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'cep' => 'nullable|string|max:9',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'country' => 'required|string|max:50',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:MALE,FEMALE,OTHER',
            'marital_status' => 'nullable|in:single,married,divorced,widow,stable union',
            'company_dept' => 'nullable|string|max:100',
            'job_position' => 'nullable|string|max:100',
        ]);

        // Atualiza os campos da entidade Person com os dados da requisição
        $person->setPersonName($request->input('person_name'));
        $person->setAddress($request->input('address'));
        $person->setComplement($request->input('complement'));
        $person->setMainPhone($request->input('main_phone'));
        $person->setMainEmail($request->input('main_email'));
        $person->setRg($request->input('rg'));
        $person->setCpf($request->input('cpf'));
        $person->setCep($request->input('cep'));
        $person->setNeighborhood($request->input('neighborhood'));
        $person->setCity($request->input('city'));
        $person->setState($request->input('state'));
        $person->setCountry($request->input('country'));

        // Lida com o campo de data de nascimento
        $birthdate = $request->input('birthdate');
        if ($birthdate) {
            try {
                $person->setBirthdate(new DateTime($birthdate));
            } catch (\Exception $e) {
                // Em caso de erro na data, pode-se logar ou retornar um erro específico
                return redirect()->back()->withInput()->withErrors(['birthdate' => 'Formato de data de nascimento inválido.']);
            }
        } else {
            $person->setBirthdate(null);
        }

        $person->setSex($request->input('sex'));
        $person->setMaritalStatus($request->input('marital_status'));
        $person->setCompanyDept($request->input('company_dept'));
        $person->setJobPosition($request->input('job_position'));

        // Persiste as mudanças no banco de dados
        $this->entityManager->flush();

        // Redireciona de volta para a lista de leads do vendedor com uma mensagem de sucesso
        // Você pode precisar ajustar esta rota de redirecionamento para onde o usuário deve ir após a edição.
        // Por exemplo, para a página de detalhes do vendedor ou de volta à lista de leads.
        // Por enquanto, vou redirecionar para a página anterior.
        return redirect()->back()->with('success', 'Dados do Lead atualizados com sucesso!');
    }
}
