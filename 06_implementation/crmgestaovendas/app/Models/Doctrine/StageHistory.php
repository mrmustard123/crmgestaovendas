<?php
/*
Author: Leonardo G. Tellez Saucedo
Email: leonardo616@gmail.com
*/
namespace App\Models\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora
//use DateTime;



#[ORM\Entity]
#[ORM\Table(name: "stage_history")]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'stage_history_unique', columns: ['fk_opportunity', 'fk_stage'])]
#[ORM\Index(name: 'fk_stage4', columns: ['fk_stage'])]            
class StageHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", name: "stage_hist_id", options: ["unsigned" => true])]
    private int $stage_hist_id;

    // `won_lost` enum('won','lost') DEFAULT NULL
    #[ORM\Column(type: "string", length: 4, nullable: true, enumType: null)] // Longitud suficiente para 'won' o 'lost'
    private ?string $won_lost = null;
    
    // `stage_hist_date` date NOT NULL
    #[ORM\Column(type: "datetime", name: "stage_hist_date")]
    private \DateTime $stage_hist_date;
    
    // `comments` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $comments = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(targetEntity: Opportunity::class)]
    #[ORM\JoinColumn(name: "fk_opportunity", referencedColumnName: "opportunity_id", onDelete: "RESTRICT")]
    private ?Opportunity $fk_opportunity;

    #[ORM\ManyToOne(targetEntity: Stage::class)]
    #[ORM\JoinColumn(name: "fk_stage", referencedColumnName: "stage_id", onDelete: "RESTRICT")]
    private ?Stage $fk_stage;


    // --- Constructor ---
    public function __construct(/*Opportunity $opportunity, Stage $stage*/)
    {
        /*$this->opportunity = $opportunity;
        $this->stage = $stage; */
    }

    // --- Getters y Setters ---
    
        
    public function getStageHistId(): int
    {
        return $this->stage_hist_id;
    }
    
    public function setStageHistId(int $stage_hist_id)
    {
        $this->stage_hist_id = $stage_hist_id;
        return $this;
    }
    
    public function getWonLost(): ?string
    {
        return $this->won_lost;
    }

    public function setWonLost(?string $won_lost): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($won_lost !== null && !in_array($won_lost, ['won', 'lost'])) {
            throw new \InvalidArgumentException("Invalid won_lost value.");
        }
        $this->won_lost = $won_lost;
        return $this;
    }

    public function getStageHistDate(): \DateTime
    {
        return $this->stage_hist_date;
    }

    public function setStageHistDate(\DateTime $stage_hist_date): self
    {
        $this->stage_hist_date = $stage_hist_date;
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

    public function getOpportunity(): Opportunity
    {
        return $this->fk_opportunity;
    }

    public function setOpportunity(Opportunity $opportunity): self
    {
        $this->fk_opportunity = $opportunity;
        return $this;
    }

    public function getStage(): Stage
    {
        return $this->fk_stage;
    }

    public function setStage(Stage $stage): self
    {
        $this->fk_stage = $stage;
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