<?php

namespace App\Models\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora inmutables

#[ORM\Entity]
#[ORM\Table(name: "product_service")] // Mapea a la tabla 'product_service'
#[ORM\HasLifecycleCallbacks] // Necesario para los timestamps automáticos
class ProductService
{
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $product_service_id;

    #[ORM\Column(type: "string", length: 255, name: "product_name")]
    private string $name; // Mapeado a 'product_name' en DB

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // Para el ENUM 'type', Doctrine no tiene un tipo ENUM nativo.
    // Lo mapeamos como string y manejamos la validación en la aplicación.
    #[ORM\Column(type: "string", length: 7, nullable: true)] // 'product' o 'service'
    private ?string $type = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(type: "decimal", precision: 15, scale: 2, name: "unit_price", nullable: true)]
    private ?float $price = null; // Mapeado a 'unit_price' en DB

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(type: "decimal", precision: 15, scale: 2, name: "tax_rate", nullable: true)]
    private ?float $taxRate = null;

    #[ORM\Column(type: "boolean", name: "is_active", options: ["default" => 1])]
    private bool $isActive = true;

    #[ORM\Column(type: "string", length: 12, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(type: "boolean", name: "is_tangible", options: ["default" => 1])]
    private bool $isTangible = true;

    #[ORM\Column(type: "datetime_immutable", name: "created_at", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(type: "datetime_immutable", name: "updated_at", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;

    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si es necesario
    }

    // --- Getters y Setters ---

    public function getProductServiceId(): int
    {
        return $this->product_service_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
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
        return $this->taxRate;
    }

    public function setTaxRate(?float $taxRate): self
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
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
        return $this->isTangible;
    }

    public function setIsTangible(bool $isTangible): self
    {
        $this->isTangible = $isTangible;
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
    public function setCreatedAndUpdatedValues(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new DateTimeImmutable();
        }
        if ($this->updated_at === null) {
            $this->updated_at = new DateTimeImmutable();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedValue(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }
}