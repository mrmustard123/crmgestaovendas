<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "stage")] // Mapea a la tabla 'stage'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Stage
{
    // `stage_id` tinyint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["unsigned" => true])] // tinyint se mapea a smallint en Doctrine
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $stage_id;

    // `stage_name` varchar(100) NOT NULL
    #[ORM\Column(type: "string", length: 100)]
    private string $stage_name;

    // `description` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $description = null;

    // `stage_order` tinyint NOT NULL DEFAULT '0'
    #[ORM\Column(type: "smallint", options: ["default" => 0])] // tinyint se mapea a smallint en Doctrine
    private int $stage_order = 0;

    // `active` tinyint NOT NULL DEFAULT '1'
    #[ORM\Column(type: "boolean", options: ["default" => 1])] // tinyint(1) se mapea a boolean en Doctrine
    private bool $active = true;

    // `color_hex` varchar(7) NOT NULL DEFAULT '#007bff'
    #[ORM\Column(type: "string", length: 7, options: ["default" => "#007bff"])]
    private string $color_hex = '#007bff';

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
        // $this->stage_order = 0; // Ya definido en la propiedad
        // $this->active = true; // Ya definido en la propiedad
        // $this->color_hex = '#007bff'; // Ya definido en la propiedad
    }

    // --- Getters y Setters ---
    public function getStageId(): int
    {
        return $this->stage_id;
    }

    public function getStageName(): string
    {
        return $this->stage_name;
    }

    public function setStageName(string $stage_name): self
    {
        $this->stage_name = $stage_name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStageOrder(): int
    {
        return $this->stage_order;
    }

    public function setStageOrder(int $stage_order): self
    {
        $this->stage_order = $stage_order;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getColorHex(): string
    {
        return $this->color_hex;
    }

    public function setColorHex(string $color_hex): self
    {
        $this->color_hex = $color_hex;
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