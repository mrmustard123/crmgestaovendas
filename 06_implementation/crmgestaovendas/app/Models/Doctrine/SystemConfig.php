<?php
/*
Author: Leonardo G. Tellez Saucedo

*/
namespace App\Models\Doctrine; // Ajusta el namespace según tu configuración

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable; // Para los campos de fecha y hora

#[ORM\Entity]
#[ORM\Table(name: "system_config")] // Mapea a la tabla 'system_config'
#[ORM\HasLifecycleCallbacks] // Necesario si usas PrePersist/PreUpdate para updated_at
class SystemConfig
{
    // `config_id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY
    #[ORM\Id]
    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private int $config_id;

    // `config_key` varchar(100) NOT NULL UNIQUE
    #[ORM\Column(type: "string", length: 100, unique: true)]
    private string $config_key;

    // `config_value` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $config_value = null;

    // `description` text DEFAULT NULL
    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    // `type` enum('string','number','boolean','json') NOT NULL DEFAULT 'string'
    #[ORM\Column(type: "string", length: 10, options: ["default" => "string"])] // Longitud suficiente para el ENUM más largo
    private string $type = 'string';

    // `category` varchar(50) DEFAULT NULL
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $category = null;

    // `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    // Solo updated_at, no created_at en tu DDL
    #[ORM\Column(type: "datetime_immutable", options: ["default" => "CURRENT_TIMESTAMP"])]
    private DateTimeImmutable $updated_at;


    // --- Constructor (Opcional) ---
    public function __construct()
    {
        // Puedes inicializar valores por defecto aquí si no los defines en la propiedad
        // $this->type = 'string'; // Ya definido en la propiedad
    }

    // --- Getters y Setters ---
    public function getConfigId(): int
    {
        return $this->config_id;
    }

    public function getConfigKey(): string
    {
        return $this->config_key;
    }

    public function setConfigKey(string $config_key): self
    {
        $this->config_key = $config_key;
        return $this;
    }

    public function getConfigValue(): ?string
    {
        return $this->config_value;
    }

    public function setConfigValue(?string $config_value): self
    {
        $this->config_value = $config_value;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        // Opcional: Validar que el valor esté en el ENUM
        if (!in_array($type, ['string', 'number', 'boolean', 'json'])) {
            throw new \InvalidArgumentException("Invalid type value.");
        }
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

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Lifecycle Callback para updated_at (ya que no hay created_at)
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }
}