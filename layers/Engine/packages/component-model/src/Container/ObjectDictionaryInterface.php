<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container;

interface ObjectDictionaryInterface
{
    public function get(string $class, $id);
    public function has(string $class, $id): bool;
    public function set(string $class, $id, $instance): void;
}
