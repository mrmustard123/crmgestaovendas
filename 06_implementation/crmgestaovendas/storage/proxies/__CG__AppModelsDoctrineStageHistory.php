<?php

namespace DoctrineProxies\__CG__\App\Models\Doctrine;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class StageHistory extends \App\Models\Doctrine\StageHistory implements \Doctrine\ORM\Proxy\InternalProxy
{
    use \Symfony\Component\VarExporter\LazyGhostTrait {
        initializeLazyObject as private;
        setLazyObjectAsInitialized as public __setInitialized;
        isLazyObjectInitialized as private;
        createLazyGhost as private;
        resetLazyObject as private;
    }

    public function __load(): void
    {
        $this->initializeLazyObject();
    }
    

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        "\0".parent::class."\0".'comments' => [parent::class, 'comments', null, 16],
        "\0".parent::class."\0".'created_at' => [parent::class, 'created_at', null, 16],
        "\0".parent::class."\0".'opportunity' => [parent::class, 'opportunity', null, 16],
        "\0".parent::class."\0".'stage' => [parent::class, 'stage', null, 16],
        "\0".parent::class."\0".'stage_hist_date' => [parent::class, 'stage_hist_date', null, 16],
        "\0".parent::class."\0".'updated_at' => [parent::class, 'updated_at', null, 16],
        "\0".parent::class."\0".'won_lost' => [parent::class, 'won_lost', null, 16],
        'comments' => [parent::class, 'comments', null, 16],
        'created_at' => [parent::class, 'created_at', null, 16],
        'opportunity' => [parent::class, 'opportunity', null, 16],
        'stage' => [parent::class, 'stage', null, 16],
        'stage_hist_date' => [parent::class, 'stage_hist_date', null, 16],
        'updated_at' => [parent::class, 'updated_at', null, 16],
        'won_lost' => [parent::class, 'won_lost', null, 16],
    ];

    public function __isInitialized(): bool
    {
        return isset($this->lazyObjectState) && $this->isLazyObjectInitialized();
    }

    public function __serialize(): array
    {
        $properties = (array) $this;
        unset($properties["\0" . self::class . "\0lazyObjectState"]);

        return $properties;
    }
}
