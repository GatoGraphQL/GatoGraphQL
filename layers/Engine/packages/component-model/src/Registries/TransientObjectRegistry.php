<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;

class TransientObjectRegistry implements TransientObjectRegistryInterface
{
    /**
     * @var array<string|int,TransientObjectInterface>
     */
    protected array $transientObjects = [];

    public function addTransientObject(TransientObjectInterface $transientObject): void
    {
        $this->transientObjects[$transientObject->getID()] = $transientObject;
    }

    public function getTransientObject(string|int $id): ?TransientObjectInterface
    {
        return $this->transientObjects[$id] ?? null;
    }
}
