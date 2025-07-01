<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTime; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "lead_origin")] // Mapea a la tabla 'lead_origin'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class LeadOrigin
{
    // `lead_origin_id` tinyint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["unsigned" => true])] // tinyint se mapea a smallint en Doctrine
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $lead_origin_id;

    // `origin` varchar(15) NOT NULL
    #[ORM\Column(type: "string", length: 15)]
    private string $origin;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $updated_at = null;


    // --- Getters y Setters ---
    public function getLeadOriginId(): int
    {
        return $this->lead_origin_id;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;
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