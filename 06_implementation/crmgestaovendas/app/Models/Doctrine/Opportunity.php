<?php

namespace App\Models\Doctrine; 

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora
use DateTime;
use App\Models\Doctrine\Stage;
use App\Models\Doctrine\ProdServOpp;
use Doctrine\Common\Collections\ArrayCollection; // ¡NUEVO! Importar para colecciones
use Doctrine\Common\Collections\Collection;     // ¡NUEVO! Importar la interfaz de colección
use Doctrine\ORM\EntityManagerInterface;


#[ORM\Entity]
#[ORM\Table(name: "opportunity")] // Mapea a la tabla 'opportunity'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps    
class Opportunity
{
    // `opportunity_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $opportunity_id;

    // `opportunity_name` varchar(200) NOT NULL
    #[ORM\Column(type: "string", length: 200)]
    private string $opportunity_name;

    // `description` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // `estimated_sale` decimal(12,2) NOT NULL DEFAULT '0.00'
    #[ORM\Column(type: "decimal", precision: 12, scale: 2, options: ["default" => "0.00"])]
    private float $estimated_sale = 0.00;
    
    #[ORM\Column(type: "decimal", precision: 12, scale: 2, options: ["default" => "0.00"])]
    private float $final_price = 0.00; 

    // `expected_closing_date` date DEFAULT NULL
    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $expected_closing_date = null;

    // `currency` varchar(3) DEFAULT NULL
    #[ORM\Column(type: "string", length: 3, nullable: true)]
    private ?string $currency = null;

    // `open_date` date DEFAULT NULL
    #[ORM\Column(type: "date", nullable: true)]
    private ?DateTime $open_date = null;

    // `lead_origin_id` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con LeadOrigin
    #[ORM\ManyToOne(targetEntity: LeadOrigin::class)]
    #[ORM\JoinColumn(name: "lead_origin_id", referencedColumnName: "lead_origin_id", nullable: true, onDelete: "SET NULL")]
    private ?LeadOrigin $lead_origin_id = null;

    // `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Low'
    #[ORM\Column(type: "string", length: 10, options: ["default" => "Low"])] // Longitud suficiente para el ENUM más largo
    private string $priority = 'Low';

    // `fk_op_status_id` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con OpportunityStatus
    #[ORM\ManyToOne(targetEntity: OpportunityStatus::class)]
    #[ORM\JoinColumn(name: "fk_op_status_id", referencedColumnName: "opportunity_status_id", nullable: true, onDelete: "SET NULL")]
    private ?OpportunityStatus $fk_op_status_id = null;  
     

    // `fk_vendor` int unsigned DEFAULT NULL
    // Relación ManyToOne con Vendor
    #[ORM\ManyToOne(targetEntity: Vendor::class)]
    #[ORM\JoinColumn(name: "fk_vendor", referencedColumnName: "vendor_id", nullable: true, onDelete: "SET NULL")]
    private ?Vendor $vendor = null;

    // `fk_person` int unsigned DEFAULT NULL
    // Relación ManyToOne con Person
    #[ORM\ManyToOne(targetEntity: Person::class)]
    #[ORM\JoinColumn(name: "fk_person", referencedColumnName: "person_id", nullable: true, onDelete: "SET NULL")]
    private ?Person $person = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;
    
// --- NUEVAS RELACIONES OneToMany ---    
    // Relación con Actividades: una Oportunidad tiene muchas Actividades
    // 'opportunity' es el nombre de la propiedad en la entidad Activity que mapea de vuelta a Opportunity
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: "opportunity", cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $activities;

    // Relación con Documentos: una Oportunidad tiene muchos Documentos
    // 'opportunity' es el nombre de la propiedad en la entidad Document que mapea de vuelta a Opportunity
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: "opportunity", cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $documents;  

    #[ORM\OneToMany(targetEntity: ProdServOpp::class, mappedBy: "opportunity", cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $prodServOpps; // Nombre de la propiedad en plural y significativo
    
    

    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->estimated_sale = 0.00; // Ya definido en la propiedad
        // $this->priority = 'Low'; // Ya definido en la propiedad
        
        $this->activities = new ArrayCollection(); // Inicializar la colección
        $this->documents = new ArrayCollection();   // Inicializar la colección  
        $this->prodServOpps = new ArrayCollection();
        
    }
        
    
    
    // --- Getters y Setters ---


    public function getOpportunityId(): int
    {
        return $this->opportunity_id;
    }

    public function getOpportunityName(): string
    {
        return $this->opportunity_name;
    }

    public function setOpportunityName(string $opportunity_name): self
    {
        $this->opportunity_name = $opportunity_name;
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

    public function getEstimatedSale(): float
    {
        return $this->estimated_sale;
    }

    public function setEstimatedSale(float $estimated_sale): self
    {
        $this->estimated_sale = $estimated_sale;
        return $this;
    }
    
    public function getFinalPrice(): float
    {
        return $this->final_price;
    }

    public function setFinalPrice(float $final_price): self
    {
        $this->final_price = $final_price;
        return $this;
    }    

    public function getExpectedClosingDate(): ?\DateTime
    {
        return $this->expected_closing_date;
    }

    public function setExpectedClosingDate(?\DateTime $expected_closing_date): self
    {
        $this->expected_closing_date = $expected_closing_date;
        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getOpenDate(): ?\DateTime
    {
        return $this->open_date;
    }

    public function setOpenDate(?\DateTime $open_date): self
    {
        $this->open_date = $open_date;
        return $this;
    }

    public function getLeadOrigin(): ?LeadOrigin
    {
        return $this->lead_origin_id;
    }

    public function setLeadOrigin(?LeadOrigin $leadOrigin): self
    {
        $this->lead_origin_id = $leadOrigin;
        return $this;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if (!in_array($priority, ['Low', 'Medium', 'High', 'Critical'])) {
            throw new \InvalidArgumentException("Invalid priority value.");
        }
        $this->priority = $priority;
        return $this;
    }

    public function getOpportunityStatus(): ?OpportunityStatus
    {
        return $this->fk_op_status_id;
    }       

    public function setOpportunityStatus(?OpportunityStatus $opportunityStatus): self
    {
        $this->fk_op_status_id = $opportunityStatus;
        return $this;
    }
    
    
    public function getOpportunityStage(): ?Stage
    {
        return $this->opportunityStage;
    }       
    
    

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;
        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;
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
    
  // --- NUEVOS Getters y Métodos para las colecciones ---        
   /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setOpportunity($this);
        }
        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getOpportunity() === $this) {
                $activity->setOpportunity(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setOpportunity($this);
        }
        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getOpportunity() === $this) {
                $document->setOpportunity(null);
            }
        }
        return $this;
    }    



    /**
     * @return Collection<int, ProdServOpp>
     */
    public function getProdServOpps(): Collection
    {
        return $this->prodServOpps;
    }

    public function addProdServOpp(ProdServOpp $prodServOpp): self
    {
        if (!$this->prodServOpps->contains($prodServOpp)) {
            $this->prodServOpps[] = $prodServOpp;
            $prodServOpp->setOpportunity($this);
        }
        return $this;
    }

    public function removeProdServOpp(ProdServOpp $prodServOpp): self
    {
        if ($this->prodServOpps->removeElement($prodServOpp)) {
            // set the owning side to null (unless already changed)
            if ($prodServOpp->getOpportunity() === $this) {
                $prodServOpp->setOpportunity(null);
            }
        }
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