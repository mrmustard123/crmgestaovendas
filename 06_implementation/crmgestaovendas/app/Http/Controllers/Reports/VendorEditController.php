<?php

/*
File: vendor edit controller
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\Vendor;
use App\Models\Doctrine\User;
use App\Models\Doctrine\UsersGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use DateTimeImmutable; // Para los campos de fecha y hora

class VendorEditController extends Controller
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Exibe o formulário de edição para um vendedor e seu usuário associado.
     *
     * @param int $vendorId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $vendorId)
    {
        $vendor = $this->entityManager->find(Vendor::class, $vendorId);

        if (!$vendor) {
            return redirect()->route('reports.vendor_performance')->with('error', 'Vendedor não encontrado.');
        }

        // Carregar o usuário associado ao vendedor
        $user = $vendor->getUser();

        // Obter o grupo "Restrito" para preselecionar o checkbox
        $restrictedGroup = $this->entityManager->getRepository(UsersGroup::class)->findOneBy(['group_name' => 'Restrito']);

        return view('admin.vendors.edit', compact('vendor', 'user', 'restrictedGroup'));
    }

    /**
     * Atualiza os dados de um vendedor e seu usuário associado.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $vendorId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $vendorId)
    {
        // 1. Validação dos dados
        $request->validate([
            // Regras de validação para o Usuário
            'username' => [
                'required',
                'string',
                'max:191',
                // Garante que o username é único, exceto para o usuário atual
                Rule::unique('App\Models\Doctrine\User', 'username')->ignore($request->input('user_id'), 'user_id'),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:191',
                Rule::unique('App\Models\Doctrine\User', 'email')->ignore($request->input('user_id'), 'user_id'),
            ],
            'password' => 'nullable|string|min:8|confirmed', // Senha opcional, mas confirmada se preenchida
            'restricted' => 'boolean', // O checkbox "Restrito"

            // Regras de validação para o Vendedor
            'vendor_name' => 'required|string|max:200',
            'main_phone' => 'nullable|string|max:20',
            'main_email' => 'nullable|string|email|max:255',
            'address' => 'nullable|string|max:100',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'country' => 'required|string|max:50',
        ]);

        // 2. Buscar as entidades
        $vendor = $this->entityManager->find(Vendor::class, $vendorId);

        if (!$vendor) {
            return redirect()->route('reports.vendor_performance')->with('error', 'Vendedor não encontrado para atualização.');
        }

        $user = $vendor->getUser(); // Obter o usuário associado

        // 3. Atualizar dados do Usuário (se existir)
        if ($user) {
            $user->setUsername($request->input('username'));
            $user->setEmail($request->input('email'));

            // Atualizar senha apenas se foi fornecida
            if ($request->filled('password')) {
                $user->setPassword(Hash::make($request->input('password')));
            }

            // Lógica para o checkbox "Restrito"
            $restrictedChecked = $request->has('restricted');
            $restrictedGroup = $this->entityManager->getRepository(UsersGroup::class)->findOneBy(['group_name' => 'Restrito']);

            if ($restrictedChecked) {
                if ($restrictedGroup) {
                    $user->setUsersGroup($restrictedGroup);
                } else {
                    // Opcional: Criar o grupo "Restrito" se não existir
                    // Ou logar um erro, ou retornar uma mensagem para o usuário
                    // Por simplicidade, vamos assumir que o grupo "Restrito" já existe.
                    // Se não existir, o usuário manterá seu grupo atual.
                    session()->flash('warning', 'O grupo "Restrito" não foi encontrado. O grupo do usuário não foi alterado.');
                }
            } else {
                // Se "Restrito" não está marcado, e o usuário está no grupo "Restrito",
                // você precisará definir para qual grupo ele deve ir.
                // Por exemplo, para um grupo padrão ou nulo.
                // Para este exemplo, vamos definir como nulo se desmarcado e estava "Restrito".
                // Em um cenário real, você teria uma lógica mais robusta para o grupo padrão.
                if ($user->getUsersGroup() && $user->getUsersGroup()->getGroupName() === 'Restrito') {
                    
                    $defaultGroup = $this->entityManager->getRepository(UsersGroup::class)->findOneBy(['group_name' => 'Vendedores']);
                    $user->setUsersGroup($defaultGroup);
                }
            }
            $user->setUpdated(new DateTimeImmutable()); // Atualiza o timestamp
        }

        // 4. Atualizar dados do Vendedor
        $vendor->setVendorName($request->input('vendor_name'));
        $vendor->setMainPhone($request->input('main_phone'));
        $vendor->setMainEmail($request->input('main_email'));
        $vendor->setAddress($request->input('address'));
        $vendor->setComplement($request->input('complement'));
        $vendor->setNeighborhood($request->input('neighborhood'));
        $vendor->setCity($request->input('city'));
        $vendor->setState($request->input('state'));
        $vendor->setCountry($request->input('country'));
        $vendor->setUpdatedAt(new DateTimeImmutable()); // Atualiza o timestamp

        // 5. Persistir as mudanças
        try {
            $this->entityManager->flush(); // Persiste todas as mudanças no banco de dados
            return redirect()->route('reports.vendor_performance')->with('success', 'Vendedor e usuário atualizados com sucesso!');
        } catch (\Exception $e) {
            // Logar o erro para depuração
            \Log::error('Erro ao atualizar vendedor e usuário: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar vendedor e usuário: ' . $e->getMessage());
        }
    }
}

