<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

class ObjectDictionary implements ObjectDictionaryInterface
{
    /**
     * @var array<string, array<string|int,mixed>>
     */
    protected array $dictionary = [];

    public function get(string $class, string|int $id): mixed
    {
        return $this->dictionary[$class][$id] ?? null;
    }
    public function has(string $class, string|int $id): bool
    {
        // The stored item can also be null!
        return array_key_exists($id, $this->dictionary[$class] ?? []);
    }
    public function set(string $class, string|int $id, mixed $instance): void
    {
        $this->dictionary[$class][$id] = $instance;
    }
}
