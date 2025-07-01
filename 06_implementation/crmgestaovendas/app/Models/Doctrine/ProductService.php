<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTime; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "product_service")] // Mapea a la tabla 'product_service'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class ProductService
{
    // `product_service_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $product_service_id;

    // `product_name` varchar(255) NOT NULL
    #[ORM\Column(type: "string", length: 255)]
    private string $product_name;

    // `description` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // `type` enum('product','service') DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)] // Longitud suficiente para el ENUM más largo
    private ?string $type = null;

    // `category` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $category = null;

    // `unit_price` decimal(15,2) DEFAULT NULL
    #[ORM\Column(type: "decimal", precision: 15, scale: 2, nullable: true)]
    private ?float $unit_price = null;

    // `unit` varchar(50) DEFAULT NULL
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $unit = null;

    // `tax_rate` decimal(15,2) DEFAULT NULL
    #[ORM\Column(type: "decimal", precision: 15, scale: 2, nullable: true)]
    private ?float $tax_rate = null;

    // `is_active` tinyint NOT NULL DEFAULT '1'
    #[ORM\Column(type: "boolean", options: ["default" => 1])] // tinyint(1) se mapea a boolean en Doctrine
    private bool $is_active = true;

    // `sku` varchar(12) DEFAULT NULL
    #[ORM\Column(type: "string", length: 12, nullable: true)]
    private ?string $sku = null;

    // `is_tangible` tinyint NOT NULL DEFAULT '1'
    #[ORM\Column(type: "boolean", options: ["default" => 1])] // tinyint(1) se mapea a boolean en Doctrine
    private bool $is_tangible = true;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $updated_at = null;


    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->is_active = true; // Ya definido en la propiedad
        // $this->is_tangible = true; // Ya definido en la propiedad
    }

    // --- Getters y Setters ---
    public function getProductServiceId(): int
    {
        return $this->product_service_id;
    }

    public function getProductName(): string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($type !== null && !in_array($type, ['product', 'service'])) {
            throw new \InvalidArgumentException("Invalid type value.");
        }
        $this->type = $type;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unit_price;
    }

    public function setUnitPrice(?float $unit_price): self
    {
        $this->unit_price = $unit_price;
        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    public function getTaxRate(): ?float
    {
        return $this->tax_rate;
    }

    public function setTaxRate(?float $tax_rate): self
    {
        $this->tax_rate = $tax_rate;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    public function isTangible(): bool
    {
        return $this->is_tangible;
    }

    public function setIsTangible(bool $is_tangible): self
    {
        $this->is_tangible = $is_tangible;
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