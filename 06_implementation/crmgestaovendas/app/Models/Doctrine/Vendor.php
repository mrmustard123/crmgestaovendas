<?php

/*
File: vendor
Author: Leonardo G. Tellez Saucedo
Created on: 4 jul. de 2025 10:36:32

*/


namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "vendor")] // Mapea a la tabla 'vendor'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para timestamps
class Vendor
{
    // `vendor_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $vendor_id;

    // `vendor_name` varchar(200) NOT NULL
    #[ORM\Column(type: "string", length: 200)]
    private string $vendor_name;

    // `main_phone` varchar(20) DEFAULT NULL
    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $main_phone = null;

    // `main_email` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $main_email = null;

    // `address` varchar(100) DEFAULT NULL
    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $address = null;

    // `complement` varchar(255) DEFAULT NULL
    #[ORM\Column(type: "string", length: 255, nullable: true)]
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

    // `fk_user` int unsigned DEFAULT NULL
    // Relación ManyToOne con User
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "fk_user", referencedColumnName: "user_id", nullable: true, onDelete: "SET NULL")]
    private ?User $user = null; // Propiedad para la relación

    // `created_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $created_at = null;

    // `updated_at` timestamp NULL DEFAULT NULL
    #[ORM\Column(type: "datetime_immutable", nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?DateTimeImmutable $updated_at = null;


    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->country = 'Brasil'; // Ya definido en la propiedad
    }

    // --- Getters y Setters ---
    public function getVendorId(): int
    {
        return $this->vendor_id;
    }

    public function getVendorName(): string
    {
        return $this->vendor_name;
    }

    public function setVendorName(string $vendor_name): self
    {
        $this->vendor_name = $vendor_name;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
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