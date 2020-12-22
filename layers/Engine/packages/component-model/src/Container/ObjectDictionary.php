<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

class ObjectDictionary implements ObjectDictionaryInterface
{
    /**
     * @var array<string, array>
     */
    protected array $dictionary = [];

    public function get(string $class, $id)
    {
        return $this->dictionary[$class][$id];
    }
    public function has(string $class, $id): bool
    {
        return isset($this->dictionary[$class][$id]);
    }
    public function set(string $class, $id, $instance): void
    {
        $this->dictionary[$class][$id] = $instance;
    }
}
