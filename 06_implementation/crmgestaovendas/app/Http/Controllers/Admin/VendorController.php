<?php
/*
File: vendorcontroller.php
Author: Leonardo G. Tellez Saucedo
Created on: 4 jul. de 2025 10:39:42
Email: leonardo616@gmail.com
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Models\Doctrine\User; // Asegúrate de que tu entidad User esté aquí
use App\Models\Doctrine\UsersGroup; // Asegúrate de que tu entidad UsersGroup esté aquí
use App\Models\Doctrine\Vendor; // Tu nueva entidad Vendor
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para crear un nuevo vendedor.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Puedes pasar datos necesarios a la vista, por ejemplo, los estados de Brasil
        // Para simplificar, de momento no pasaremos estados.
        return view('admin.vendors.create');
    }

    /**
     * Almacena un nuevo vendedor y su usuario asociado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Usaremos una transacción para asegurar que tanto el usuario como el vendedor se creen correctamente
        $this->entityManager->beginTransaction();

        try {
            // 1. Validación de los datos del formulario (Usuario y Vendedor)
            $request->validate([
                // Datos del Usuario
                'username'              => ['required', 'string', 'max:255', 'unique:' . User::class . ',username'],
                'password'              => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required'],

                // Datos del Vendedor
                'vendor_name'  => ['required', 'string', 'max:200'],
                'main_phone'   => ['nullable', 'string', 'max:20'],
                'main_email'   => ['nullable', 'email', 'max:255', 'unique:' . Vendor::class . ',main_email'], // Email del vendedor podría ser único
                'address'      => ['nullable', 'string', 'max:100'],
                'complement'   => ['nullable', 'string', 'max:255'],
                'neighborhood' => ['nullable', 'string', 'max:100'],
                'city'         => ['nullable', 'string', 'max:100'],
                'state'        => ['nullable', 'string', 'max:2'],
                'country'      => ['nullable', 'string', 'max:50'], // Puede venir del formulario o usar default 'Brasil'
            ], [
                // Mensajes de error personalizados para el usuario
                'username.required'              => 'O nome de usuário é obrigatório.',
                'username.unique'                => 'Este nome de usuário já está em uso.',
                'password.required'              => 'A senha é obrigatória.',
                'password.min'                   => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed'             => 'A confirmação de senha não coincide.',
                'password_confirmation.required' => 'A confirmação de senha é obrigatória.',

                // Mensajes de error personalizados para el vendedor
                'vendor_name.required' => 'O nome do vendedor é obrigatório.',
                'main_email.email'     => 'O email do vendedor deve ser um endereço válido.',
                'main_email.unique'    => 'Este email de vendedor já está em uso.',
            ]);

            // 2. Obtener el UsersGroup para 'Vendedores'
            // Es crucial que este grupo exista en tu base de datos.
            $usersGroupRepository = $this->entityManager->getRepository(UsersGroup::class);
            $vendorGroup = $usersGroupRepository->findOneBy(['group_name' => 'Vendedores']);

            if (!$vendorGroup) {
                // Si el grupo 'Vendedores' no existe, lanza un error o crea el grupo
                // Para este caso, vamos a lanzarlo como una excepción.
                throw new \Exception("Grupo 'Vendedores' não encontrado. Por favor, crie este grupo primeiro.");
            }

            // 3. Crear el nuevo Usuario
            $user = new User();
            $user->setUsername($request->username);
            $user->setPassword(Hash::make($request->password)); // Hashea la contraseña
            $user->setUsersGroup($vendorGroup); // Asigna el grupo 'Vendedores'            
            $this->entityManager->persist($user);
            $this->entityManager->flush(); // Guardamos el usuario para obtener su ID antes de crear el vendedor

            // 4. Crear el nuevo Vendedor
            $vendor = new Vendor();
            $vendor->setVendorName($request->vendor_name);
            $vendor->setMainPhone($request->main_phone);
            $vendor->setMainEmail($request->main_email);
            $vendor->setAddress($request->address);
            $vendor->setComplement($request->complement);
            $vendor->setNeighborhood($request->neighborhood);
            $vendor->setCity($request->city);
            $vendor->setState($request->state);
            $vendor->setCountry($request->country ?? 'Brasil'); // Usa el valor del form o 'Brasil' por defecto

            // Asigna el usuario recién creado al vendedor
            $vendor->setUser($user);

            $this->entityManager->persist($vendor);
            $this->entityManager->flush(); // Guarda el vendedor

            // 5. Si todo ha ido bien, confirma la transacción
            $this->entityManager->commit();

            return redirect()->route('admin.vendors.create')->with('success', 'Vendedor cadastrado com sucesso!');

        } catch (ValidationException $e) {
            // Si hay errores de validación, se revierte automáticamente si no se ha hecho un flush
            $this->entityManager->rollback(); // En caso de que se haya hecho un flush antes
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // En caso de cualquier otro error, revierte la transacción
            $this->entityManager->rollback();
            Log::error('Erro ao registrar vendedor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao registrar o vendedor. Por favor, tente novamente.')->withInput();
        }
    }
}