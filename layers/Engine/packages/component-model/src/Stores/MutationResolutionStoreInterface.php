<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Stores;

interface MutationResolutionStoreInterface
{
    public function clearResults(): void;

    public function setResult(object $object, mixed $result): void;

    public function getResult(object $object): mixed;
}
