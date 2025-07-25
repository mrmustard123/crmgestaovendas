<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas
use Doctrine\ORM\EntityManagerInterface; // Para interactuar con Doctrine
use App\Models\Doctrine\User; // Tu entidad User
use App\Models\Doctrine\UsersGroup; // Tu entidad UsersGroup
use Illuminate\Validation\ValidationException; // Para manejar errores de validación
use Illuminate\Support\Facades\Log; // Para registrar errores


class UserController extends Controller
{
    private $entityManager;

    // Inyecta el EntityManager a través del constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * Pasa los grupos de usuarios a la vista.
     */
    public function create()
    {
        // Obtener todos los grupos de usuarios de la base de datos
        $userGroups = $this->entityManager->getRepository(UsersGroup::class)->findAll();

        // Pasar los grupos a la vista
        return view('admin.users.create', [
            'userGroups' => $userGroups
        ]);
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            // 1. Validar los datos del formulario
            $request->validate([
                'username' => ['required', 'string', 'max:191', 'unique:' . User::class . ',username'],
                'email' => ['nullable', 'string', 'email', 'max:191', 'unique:' . User::class . ',email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' valida que 'password_confirmation' coincida
                'user_group_id' => ['required', 'integer'], // ID del grupo
            ], [
                'username.unique' => 'Este nome de usuário já está em uso.',
                'email.unique' => 'Este e-mail já está registado.',
                'password.min' => 'A senha deve ter pelo menos :min caracteres..',
                'password.confirmed' => 'A confirmação da senha não corresponde.',
                'user_group_id.required' => 'Você deve selecionar um grupo de usuários.',
                'user_group_id.integer' => 'O grupo de usuários selecionado não é válido.'
            ]);

            // 2. Buscar el objeto UsersGroup por su ID
            $usersGroup = $this->entityManager->getRepository(UsersGroup::class)->find($request->user_group_id);

            // Si el grupo no existe, esto es un problema (aunque la validación de 'integer' ayuda)
            if (!$usersGroup) {
                // Puedes lanzar una excepción o redirigir con un error
                throw ValidationException::withMessages([
                    'user_group_id' => 'O grupo de usuários selecionado não existe.'
                ]);
            }

            // 3. Crear una nueva instancia de la entidad User
            $user = new User();
            $user->setUsername($request->username);
            $user->setEmail($request->email);
            // Hashear la contraseña antes de guardarla
            $user->setPassword(Hash::make($request->password));
            $user->setUsersGroup($usersGroup); // Asignar el objeto UsersGroup

            // 4. Persistir el objeto con Doctrine
            $this->entityManager->persist($user);
            $this->entityManager->flush(); // Guarda los cambios en la base de datos

            // 5. Redirigir con un mensaje de éxito
            return redirect()->route('admin.users.create')->with('success', 'Usuário registrado com sucesso!');

        } catch (ValidationException $e) {
            // Captura errores de validación y redirige de vuelta con los errores
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Captura cualquier otra excepción general
            Log::error('Erro ao registrar usuario: ' . $e->getMessage());
            return redirect()->back()->with('erro', 'Ocorreu um erro ao registrar o usuário. Tente novamente.')->withInput();
        }
    }
}