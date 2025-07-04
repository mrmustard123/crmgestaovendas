<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "activity")] // Mapea a la tabla 'activity'
class Activity
{
    // `activity_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")] // O "IDENTITY" para bases de datos que gestionan el auto-incremento
    private int $activity_id;

    // `titulo` varchar(200) NOT NULL
    #[ORM\Column(type: "string", length: 200)]
    private string $titulo;

    // `description` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // `activity_date` date NOT NULL
    #[ORM\Column(type: "date")]
    private DateTime $activity_date;

    // `duration_min` tinyint DEFAULT NULL
    #[ORM\Column(type: "smallint", nullable: true)] // tinyint se mapea a smallint en Doctrine
    private ?int $duration_min = null;

    // `status` enum('scheduled','performed','canceled','resheduled') NOT NULL DEFAULT 'scheduled'
    // Para ENUMs, Doctrine no tiene un tipo ENUM nativo. Se mapean como string y se gestionan los valores en el código.
    #[ORM\Column(type: "string", length: 20, options: ["default" => "scheduled"])] // Ajusta la longitud máxima del ENUM
    private string $status = 'scheduled';

    // `result` enum('positive','negative','neutral') DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)] // Ajusta la longitud máxima del ENUM
    private ?string $result = null;

    // `comments` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comments = null;

    // `fk_opportunity` int unsigned DEFAULT NULL
    // Relación ManyToOne con Opportunity
    #[ORM\ManyToOne(targetEntity: Opportunity::class)] // Asume que Opportunity también será una Entidad Doctrine
    #[ORM\JoinColumn(name: "fk_opportunity", referencedColumnName: "opportunity_id", nullable: true, onDelete: "SET NULL")] // onDelete: "SET NULL" si la FK es nullable
    private ?Opportunity $opportunity = null; // Propiedad para la relación

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])] // datetime_immutable para timestamps
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])] // ON UPDATE CURRENT_TIMESTAMP se gestiona a nivel de DB o con Lifecycle Callbacks
    private ?DateTimeImmutable $updated_at = null;


    // --- Getters y Setters (Métodos para acceder y modificar las propiedades) ---
    // Doctrine requiere getters y setters para acceder a las propiedades mapeadas.

    public function getActivityId(): int
    {
        return $this->activity_id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
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

    public function getActivityDate(): DateTime
    {
        return $this->activity_date;
    }

    public function setActivityDate(DateTime $activity_date): self
    {
        $this->activity_date = $activity_date;
        return $this;
    }

    public function getDurationMin(): ?int
    {
        return $this->duration_min;
    }

    public function setDurationMin(?int $duration_min): self
    {
        $this->duration_min = $duration_min;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if (!in_array($status, ['scheduled', 'performed', 'canceled', 'resheduled'])) {
            throw new \InvalidArgumentException("Invalid status value.");
        }
        $this->status = $status;
        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($result !== null && !in_array($result, ['positive', 'negative', 'neutral'])) {
            throw new \InvalidArgumentException("Invalid result value.");
        }
        $this->result = $result;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;
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

    // Opcional: Lifecycle Callbacks para updated_at si no confías solo en la DB
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTime();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new DateTimeImmutable();
        if ($this->updated_at === null) {
            $this->updated_at = new DateTimeImmutable();
        }
    }
}