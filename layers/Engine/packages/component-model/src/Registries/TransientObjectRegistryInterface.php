<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\ObjectModels\TransientObjectInterface;

interface TransientObjectRegistryInterface
{
    public function addTransientObject(TransientObjectInterface $transientObject): void;
    public function getTransientObject(string|int $id): ?TransientObjectInterface;
}
