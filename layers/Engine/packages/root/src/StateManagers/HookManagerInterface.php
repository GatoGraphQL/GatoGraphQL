<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

interface HookManagerInterface
{
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool;
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed;
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool;
    public function doAction(string $tag, mixed ...$args): void;
}
