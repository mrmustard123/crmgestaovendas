<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTime; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "person_role")] // Mapea a la tabla 'person_role'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class PersonRole
{
    // `person_role_id` tinyint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["unsigned" => true])] // tinyint se mapea a smallint en Doctrine
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $person_role_id;

    // `role_name` varchar(15) NOT NULL
    #[ORM\Column(type: "string", length: 15)]
    private string $role_name;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $updated_at = null;


    // --- Getters y Setters ---
    public function getPersonRoleId(): int
    {
        return $this->person_role_id;
    }

    public function getRoleName(): string
    {
        return $this->role_name;
    }

    public function setRoleName(string $role_name): self
    {
        $this->role_name = $role_name;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Lifecycle Callbacks para updated_at y created_at
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new DateTime();
        }
        if ($this->updated_at === null) {
            $this->updated_at = new DateTime();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTime();
    }
}