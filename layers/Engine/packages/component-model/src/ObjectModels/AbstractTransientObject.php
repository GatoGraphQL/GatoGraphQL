<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectModels;

use PoP\ComponentModel\Registries\TransientObjectRegistryInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractTransientObject implements TransientObjectInterface
{
    use StandaloneServiceTrait;

    private ?TransientObjectRegistryInterface $transientObjectRegistry = null;

    final public function setTransientObjectRegistry(TransientObjectRegistryInterface $transientObjectRegistry): void
    {
        $this->transientObjectRegistry = $transientObjectRegistry;
    }
    final protected function getTransientObjectRegistry(): TransientObjectRegistryInterface
    {
        /** @var TransientObjectRegistryInterface */
        return $this->transientObjectRegistry ??= InstanceManagerFacade::getInstance()->getInstance(TransientObjectRegistryInterface::class);
    }

    /**
     * Static ID generator
     */
    public static int $counter = 0;

    public readonly int $id;

    public function __construct()
    {
        self::$counter++;
        $this->id = self::$counter;

        // Register the object in the registry
        $this->getTransientObjectRegistry()->addTransientObject($this);
    }

    public function getID(): int|string
    {
        return $this->id;
    }
}
