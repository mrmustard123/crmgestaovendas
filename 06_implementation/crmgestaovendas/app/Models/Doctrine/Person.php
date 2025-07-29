<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "person")] // Mapea a la tabla 'person'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Person
{
    // `person_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $person_id;

    // `person_name` varchar(255) NOT NULL
    #[ORM\Column(type: "string", length: 255)]
    private string $person_name;

    // `address` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $address = null;

    // `complement` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $complement = null;

    // `main_phone` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $main_phone = null;

    // `main_email` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $main_email = null;

    // `rg` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $rg = null;

    // `cpf` varchar(14) DEFAULT NULL
    #[ORM\Column(type: "string", length: 14, nullable: true)]
    private ?string $cpf = null;

    // `cep` varchar(9) DEFAULT NULL
    #[ORM\Column(type: "string", length: 9, nullable: true)]
    private ?string $cep = null;

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

    // `birthdate` date DEFAULT NULL
    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $birthdate = null;

    // `sex` enum('MALE','FEMALE','OTHER') DEFAULT NULL
    #[ORM\Column(type: "string", length: 10, nullable: true)] // Longitud suficiente para el ENUM más largo
    private ?string $sex = null;

    // `marital_status` enum('single','married','divorced','widow','stable union') DEFAULT NULL
    #[ORM\Column(type: "string", length: 15, nullable: true)] // Longitud suficiente para el ENUM más largo
    private ?string $marital_status = null;

    // `company_dept` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $company_dept = null;

    // `job_position` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $job_position = null;

    // `fk_person_role` tinyint unsigned DEFAULT NULL
    // Relación ManyToOne con PersonRole
    #[ORM\ManyToOne(targetEntity: PersonRole::class)]
    #[ORM\JoinColumn(name: "fk_person_role", referencedColumnName: "person_role_id", nullable: true, onDelete: "SET NULL")]
    private ?PersonRole $personRole = null;

    // `fk_company` int unsigned DEFAULT NULL
    // Relación ManyToOne con Company
    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: "fk_company", referencedColumnName: "company_id", nullable: true, onDelete: "SET NULL")]
    private ?Company $company = null;

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;


    // --- Constructor (Opcional) ---
    public function __construct()
    {
        
        // $this->country = 'Brasil'; // Ya definido en la propiedad
    }

    // --- Getters y Setters ---
    public function getPersonId(): int
    {
        return $this->person_id;
    }

    public function getPersonName(): string
    {
        return $this->person_name;
    }

    public function setPersonName(string $person_name): self
    {
        $this->person_name = $person_name;
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

    public function getRg(): ?string
    {
        return $this->rg;
    }

    public function setRg(?string $rg): self
    {
        $this->rg = $rg;
        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): self
    {
        $this->cpf = $cpf;
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

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($sex !== null && !in_array($sex, ['MALE', 'FEMALE', 'OTHER'])) {
            throw new \InvalidArgumentException("Invalid sex value.");
        }
        $this->sex = $sex;
        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->marital_status;
    }

    public function setMaritalStatus(?string $marital_status): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if ($marital_status !== null && !in_array($marital_status, ['single', 'married', 'divorced', 'widow', 'stable union'])) {
            throw new \InvalidArgumentException("Invalid marital status value.");
        }
        $this->marital_status = $marital_status;
        return $this;
    }

    public function getCompanyDept(): ?string
    {
        return $this->company_dept;
    }

    public function setCompanyDept(?string $company_dept): self
    {
        $this->company_dept = $company_dept;
        return $this;
    }

    public function getJobPosition(): ?string
    {
        return $this->job_position;
    }

    public function setJobPosition(?string $job_position): self
    {
        $this->job_position = $job_position;
        return $this;
    }

    public function getPersonRole(): ?PersonRole
    {
        return $this->personRole;
    }

    public function setPersonRole(?PersonRole $personRole): self
    {
        $this->personRole = $personRole;
        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;
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