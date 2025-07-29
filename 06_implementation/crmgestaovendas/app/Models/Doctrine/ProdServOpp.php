<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "prod_serv_opp")] // Mapea a la tabla 'prod_serv_opp'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class ProdServOpp
{
    // Clave primaria compuesta
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Opportunity::class)]
    #[ORM\JoinColumn(name: "opportunity_id", referencedColumnName: "opportunity_id", onDelete: "CASCADE")]
    private Opportunity $opportunity; // No es nullable porque es parte de la PK

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: ProductService::class)]
    #[ORM\JoinColumn(name: "product_service_id", referencedColumnName: "product_service_id", onDelete: "CASCADE")]
    private ProductService $productService; // No es nullable porque es parte de la PK

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;


    // --- Constructor ---
    public function __construct(Opportunity $opportunity, ProductService $productService)
    {
        $this->opportunity = $opportunity;
        $this->productService = $productService;
    }

    // --- Getters y Setters ---
    public function getOpportunity(): Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;
        return $this;
    }

    public function getProductService(): ProductService
    {
        return $this->productService;
    }

    public function setProductService(ProductService $productService): self
    {
        $this->productService = $productService;
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