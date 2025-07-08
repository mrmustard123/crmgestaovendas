<?php

namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "company")] // Mapea a la tabla 'company'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Company
{
    // `company_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $company_id;

    // `social_reason` varchar(200) NOT NULL
    #[ORM\Column(type: "string", length: 200)]
    private string $social_reason;

    // `fantasy_name` varchar(200) DEFAULT NULL
    #[ORM\Column(type: "string", length: 200, nullable: true)]
    private ?string $fantasy_name = null;

    // `cnpj` varchar(18) DEFAULT NULL
    #[ORM\Column(type: "string", length: 18, nullable: true)]
    private ?string $cnpj = null;

    // `inscricao_estadual` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $inscricao_estadual = null;

    // `inscricao_municipal` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $inscricao_municipal = null;

    // `cep` varchar(9) DEFAULT NULL
    #[ORM\Column(type: "string", length: 9, nullable: true)]
    private ?string $cep = null;

    // `address` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $address = null;

    // `complement` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $complement = null;

    // `neighborhood` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $neighborhood = null;

    // `city` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $city = null;

    // `state` varchar(2) DEFAULT NULL
    #[ORM\Column(type: "string", length: 2, nullable: true)]
    private ?string $state = null;

    // `country` varchar(50) NOT NULL DEFAULT 'Brasil'
    #[ORM\Column(type: "string", length: 50, options: ["default" => "Brasil"])]
    private string $country = 'Brasil';

    // `main_phone` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $main_phone = null;

    // `main_email` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $main_email = null;

    // `website` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $website = null;

    // `company_size` enum('Big','Medium','Small','Tiny') DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)] // Longitud suficiente para el ENUM más largo
    private ?string $company_size = null;

    // `status` enum('active','unactive') DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)] // Longitud suficiente para el ENUM más largo
    private ?string $status = null;

    // `comments` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $comments = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;


    // --- Constructor (Opcional, pero útil para inicializar) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->country = 'Brasil'; // Ya definido en la propiedad
        // $this->status = 'active'; // Si quisieras un default diferente al del DDL
    }

    // --- Getters y Setters ---
    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function getSocialReason(): string
    {
        return $this->social_reason;
    }

    public function setSocialReason(string $social_reason): self
    {
        $this->social_reason = $social_reason;
        return $this;
    }

    public function getFantasyName(): ?string
    {
        return $this->fantasy_name;
    }

    public function setFantasyName(?string $fantasy_name): self
    {
        $this->fantasy_name = $fantasy_name;
        return $this;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(?string $cnpj): self
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function getInscricaoEstadual(): ?string
    {
        return $this->inscricao_estadual;
    }

    public function setInscricaoEstadual(?string $inscricao_estadual): self
    {
        $this->inscricao_estadual = $inscricao_estadual;
        return $this;
    }

    public function getInscricaoMunicipal(): ?string
    {
        return $this->inscricao_municipal;
    }

    public function setInscricaoMunicipal(?string $inscricao_municipal): self
    {
        $this->inscricao_municipal = $inscricao_municipal;
        return $this;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(?string $cep): self
    {
        $this->cep = $cep;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?string $neighborhood): self
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getMainPhone(): ?string
    {
        return $this->main_phone;
    }

    public function setMainPhone(?string $main_phone): self
    {
        $this->main_phone = $main_phone;
        return $this;
    }

    public function getMainEmail(): ?string
    {
        return $this->main_email;
    }

    public function setMainEmail(?string $main_email): self
    {
        $this->main_email = $main_email;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getCompanySize(): ?string
    {
        return $this->company_size;
    }

    public function setCompanySize(?string $company_size): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($company_size !== null && !in_array($company_size, ['Big', 'Medium', 'Small', 'Tiny'])) {
            throw new \InvalidArgumentException("Invalid company size value.");
        }
        $this->company_size = $company_size;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($status !== null && !in_array($status, ['active', 'unactive'])) {
            throw new \InvalidArgumentException("Invalid status value.");
        }
        $this->status = $status;
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