<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Stores;

class MutationResolutionStore implements MutationResolutionStoreInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $results = [];

    public function clearResults(): void
    {
        $this->results = [];
    }

    public function setResult(object $object, mixed $result): void
    {
        $this->results[get_class($object)] = $result;
    }

    public function getResult(object $object): mixed
    {
        return $this->results[get_class($object)];
    }
}
