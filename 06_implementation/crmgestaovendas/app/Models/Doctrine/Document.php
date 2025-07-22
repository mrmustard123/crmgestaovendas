<?php

namespace App\Models\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; 
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "document")]
#[ORM\HasLifecycleCallbacks] // Necesario para los timestamps automáticos
class Document
{
    // `document_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $document_id;

    #[ORM\Column(type: "string", length: 255)]
    private string $file_name; // Cambiado de `doc_name` a `file_name` para el nombre original del archivo subido

    #[ORM\Column(type: "string", length: 255)]
    private string $file_path; // Ruta relativa donde se guarda el archivo en el disco (ej. documents/1/mi_doc.pdf)

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $mime_type = null; // Tipo MIME del archivo (ej. application/pdf), cambiado de `file_type` para mayor claridad

    #[ORM\Column(type: "bigint", nullable: true)] // bigint para tamaños de archivo grandes
    private ?int $file_size = null; // Tamaño del archivo en bytes, cambiado de `size_bytes`

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null; // Descripción opcional del documento

    // Relación ManyToOne con Opportunity
    #[ORM\ManyToOne(targetEntity: Opportunity::class)]
    #[ORM\JoinColumn(name: "fk_opportunity", referencedColumnName: "opportunity_id", nullable: false)] // Cambiado a `nullable: false` si un documento siempre debe tener una oportunidad
    private Opportunity $opportunity;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $uploaded_at = null; // Nuevo campo para la fecha de subida

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $created_at = null; // Para control general del registro

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $updated_at = null; // Para control general del registro

    // --- Getters y Setters ---
    public function getDocumentId(): int 
    { 
        return $this->document_id;     
    }
    public function getFileName(): string 
    { 
        return $this->file_name;         
    }
    public function setFileName(string $file_name): self 
    { 
        $this->file_name = $file_name; return $this;         
    }
    public function getFilePath(): string 
    { 
        return $this->file_path;         
    }
    public function setFilePath(string $file_path): self 
    { 
        $this->file_path = $file_path; return $this;         
    }
    public function getMimeType(): ?string 
    { 
        return $this->mime_type;         
    }
    public function setMimeType(?string $mime_type): self 
    { 
        $this->mime_type = $mime_type; return $this;         
    }
    public function getFileSize(): ?int 
    { 
        return $this->file_size;         
    }
    public function setFileSize(?int $file_size): self 
    { 
        $this->file_size = $file_size; return $this;         
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
    public function getOpportunity(): Opportunity 
    { 
        return $this->opportunity;         
    }
    
    public function setOpportunity(?Opportunity $opportunity): self 
    { 
        $this->opportunity = $opportunity; 
        return $this;         
    }
    
    public function getUploadedAt(): ?\DateTime
    { 
        return $this->uploaded_at;         
    }
    public function setUploadedAt(?\DateTime $uploaded_at): self 
    { 
        $this->uploaded_at = $uploaded_at; 
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

    // Lifecycle Callbacks para timestamps
    #[ORM\PrePersist]
    public function setTimestampsOnPersist(): void
    {
        if ($this->created_at === null) {
            $this->created_at = new DateTimeImmutable();
        }
        if ($this->updated_at === null) {
            $this->updated_at = new DateTimeImmutable();
        }
        if ($this->uploaded_at === null) { 
            $this->uploaded_at = new \DateTime();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }
}