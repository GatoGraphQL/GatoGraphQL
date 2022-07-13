<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

interface ObjectDictionaryInterface
{
    public function get(string $class, string|int $id): mixed;
    public function has(string $class, string|int $id): bool;
    public function set(string $class, string|int $id, mixed $instance): void;
}
