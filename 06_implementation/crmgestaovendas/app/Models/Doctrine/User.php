<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTime; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "users")] // Mapea a la tabla 'users'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class User // Clase 'User' en singular, como es la convención para entidades
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
    private ?DateTime $email_verified_at = null;

    // `fk_users_group` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con UsersGroup
    #[ORM\ManyToOne(targetEntity: UsersGroup::class)]
    #[ORM\JoinColumn(name: "fk_users_group", referencedColumnName: "user_group_id", nullable: true, onDelete: "SET NULL")]
    private ?UsersGroup $usersGroup = null;

    // `remember_token` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $remember_token = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $created = null; // Renombrado a 'created' para evitar conflicto si Laravel usa 'created_at'

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $updated = null; // Renombrado a 'updated' para evitar conflicto si Laravel usa 'updated_at'


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

    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?DateTime $email_verified_at): self
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

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function setRememberToken(?string $remember_token): self
    {
        $this->remember_token = $remember_token;
        return $this;
    }

    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    public function setCreated(?DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?DateTime $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    // Lifecycle Callbacks para updated_at y created_at
    #[ORM\PrePersist]
    public function setCreatedAndUpdatedValues(): void
    {
        if ($this->created === null) {
            $this->created = new DateTime();
        }
        if ($this->updated === null) {
            $this->updated = new DateTime();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedValue(): void
    {
        $this->updated = new DateTime();
    }
}