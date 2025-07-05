<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "directive")] // Mapea a la tabla 'directive'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Directive
{
    // `directive_id` tinyint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["unsigned" => true])] // tinyint se mapea a smallint en Doctrine
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $directive_id;

    // `directive` enum('ALLOW','DENY') NOT NULL
    #[ORM\Column(type: "string", length: 5)] // Longitud suficiente para 'ALLOW' o 'DENY'
    private string $directive;

    // `id_users_group` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con UsersGroup
    #[ORM\ManyToOne(targetEntity: UsersGroup::class)] // Asume que UsersGroup también será una Entidad Doctrine
    #[ORM\JoinColumn(name: "id_users_group", referencedColumnName: "user_group_id", nullable: true, onDelete: "SET NULL")]
    private ?UsersGroup $usersGroup = null; // Propiedad para la relación

    // `id_functionality` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con Functionality
    #[ORM\ManyToOne(targetEntity: Functionality::class)] // Asume que Functionality también será una Entidad Doctrine
    #[ORM\JoinColumn(name: "id_functionality", referencedColumnName: "functionality_id", nullable: true, onDelete: "SET NULL")]
    private ?Functionality $functionality = null; // Propiedad para la relación

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;


    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->directive = 'ALLOW'; // Si quisieras un default diferente al del DDL
    }

    // --- Getters y Setters ---
    public function getDirectiveId(): int
    {
        return $this->directive_id;
    }

    public function getDirective(): string
    {
        return $this->directive;
    }

    public function setDirective(string $directive): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if (!in_array($directive, ['ALLOW', 'DENY'])) {
            throw new \InvalidArgumentException("Invalid directive value.");
        }
        $this->directive = $directive;
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

    public function getFunctionality(): ?Functionality
    {
        return $this->functionality;
    }

    public function setFunctionality(?Functionality $functionality): self
    {
        $this->functionality = $functionality;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Lifecycle Callbacks para updated_at y created_at
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new DateTimeImmutable();
        }
        if ($this->updated_at === null) {
            $this->updated_at = new DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }
}