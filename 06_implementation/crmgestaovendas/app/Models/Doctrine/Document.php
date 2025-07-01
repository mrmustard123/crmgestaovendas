<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTime; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "document")] // Mapea a la tabla 'document'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Document
{
    // `document_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $document_id;

    // `doc_name` varchar(200) NOT NULL
    #[ORM\Column(type: "string", length: 200)]
    private string $doc_name;

    // `filename` varchar(255) NOT NULL
    #[ORM\Column(type: "string", length: 255)]
    private string $filename;

    // `file_type` varchar(10) DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private ?string $file_type = null;

    // `size_bytes` bigint DEFAULT NULL
    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $size_bytes = null;

    // `file_path` varchar(500) DEFAULT NULL
    #[ORM\Column(type: "string", length: 500, nullable: true)]
    private ?string $file_path = null;

    // `description` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // `fk_opportunity` int unsigned DEFAULT NULL
    // Relación ManyToOne con Opportunity
    #[ORM\ManyToOne(targetEntity: Opportunity::class)] // Asume que Opportunity también será una Entidad Doctrine
    #[ORM\JoinColumn(name: "fk_opportunity", referencedColumnName: "opportunity_id", nullable: true, onDelete: "SET NULL")]
    private ?Opportunity $opportunity = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTime $updated_at = null;


    // --- Getters y Setters ---
    public function getDocumentId(): int
    {
        return $this->document_id;
    }

    public function getDocName(): string
    {
        return $this->doc_name;
    }

    public function setDocName(string $doc_name): self
    {
        $this->doc_name = $doc_name;
        return $this;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->file_type;
    }

    public function setFileType(?string $file_type): self
    {
        $this->file_type = $file_type;
        return $this;
    }

    public function getSizeBytes(): ?int
    {
        return $this->size_bytes;
    }

    public function setSizeBytes(?int $size_bytes): self
    {
        $this->size_bytes = $size_bytes;
        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(?string $file_path): self
    {
        $this->file_path = $file_path;
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

    public function getOpportunity(): ?Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(?Opportunity $opportunity): self
    {
        $this->opportunity = $opportunity;
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