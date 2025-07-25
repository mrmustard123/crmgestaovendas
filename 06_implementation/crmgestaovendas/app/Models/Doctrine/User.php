<?php
/*
Author: Leonardo G. Tellez Saucedo
Email: leonardo616@gmail.com
*/
namespace App\Models\Doctrine; 

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora
use DateTime;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable; 

#[ORM\Entity]
#[ORM\Table(name: "users")] // Mapea a la tabla 'users'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class User implements JWTSubject, Authenticatable  // Clase 'User' en singular, como es la convención para entidades
{
    // `user_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $user_id;

    // `username` varchar(191) NOT NULL UNIQUE
    #[ORM\Column(type: "string", length: 191, unique: true)]
    private string $username;

    // `password` varchar(191) NOT NULL
    // La encriptación de la contraseña se maneja en el código de la aplicación, no en el mapeo ORM.
    #[ORM\Column(type: "string", length: 191)]
    private string $password;

    // `email` varchar(191) DEFAULT NULL UNIQUE
    #[ORM\Column(type: "string", length: 191, nullable: true, unique: true)]
    private ?string $email = null;

    // `email_verified_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTime $email_verified_at = null;

    // `fk_users_group` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con UsersGroup
    #[ORM\ManyToOne(targetEntity: UsersGroup::class)]
    #[ORM\JoinColumn(name: "fk_users_group", referencedColumnName: "user_group_id", nullable: true, onDelete: "SET NULL")]
    private ?UsersGroup $usersGroup = null;

    // `remember_token` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $remember_token = null;
    
// `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", name: "created_at", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created = null; // La propiedad sigue siendo 'created', pero mapea a 'created_at' en DB

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", name: "updated_at", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated = null; // La propiedad sigue siendo 'updated', pero mapea a 'updated_at' en DB    
    

    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí
    }

    // --- Getters y Setters ---
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        // En un contexto real, aquí se usaría Hash::make($password) al guardar la contraseña
        $this->password = $password;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?\DateTime $email_verified_at): self
    {
        $this->email_verified_at = $email_verified_at;
        return $this;
    }

    public function getUsersGroup(): ?UsersGroup
    {
        return $this->usersGroup;
    }

    public function setUsersGroup(?UsersGroup $usersGroup): self
    {
        $this->usersGroup = $usersGroup;
        return $this;
    }

    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(?DateTimeImmutable $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTimeImmutable
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeImmutable $updated): self
    {
        $this->updated = $updated;
        return $this;
    }
    
    
    // Implementación de JWTSubject
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {   // El ID único del usuario
        return $this->user_id; 
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            // Puedes añadir cualquier otro dato que quieras que esté disponible en el payload del token
            'user_group_id' => $this->usersGroup ? $this->usersGroup->getUserGroupId() : null,
            'group_name' => $this->usersGroup ? $this->usersGroup->getGroupName() : null,
        ];
    }   
    
    
    // --- Métodos de Illuminate\Contracts\Auth\Authenticatable ---

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'user_id'; // Debe ser el nombre de tu columna de ID único
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier(): mixed
    {
        return $this->user_id; // Debe devolver el valor de tu ID único
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->password; // Debe devolver la contraseña hasheada
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string|null
     */
    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    /**
     * Set the "remember me" token value.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    
    
    
    

    // Lifecycle Callbacks para updated_at y created_at
    #[ORM\PrePersist]
    public function setCreatedAndUpdatedValues(): void
    {
        if ($this->created === null) {
            $this->created = new DateTimeImmutable();
        }
        if ($this->updated === null) {
            $this->updated = new DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedValue(): void
    {
        $this->updated = new DateTimeImmutable();
    }
}