<?php

declare(strict_types=1);

namespace PoP\HooksPHP;

use PoP\Hooks\HooksAPIInterface;

class HooksAPI implements HooksAPIInterface
{
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        global $hooks;
        $hooks->add_filter($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        global $hooks;
        return $hooks->remove_filter($tag, $function_to_remove, $priority);
    }
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        global $hooks;
        return $hooks->apply_filters($tag, $value, ...$args);
    }
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        global $hooks;
        $hooks->add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        global $hooks;
        return $hooks->remove_action($tag, $function_to_remove, $priority);
    }
    public function doAction(string $tag, mixed ...$args): void
    {
        global $hooks;
        $hooks->do_action($tag, ...$args);
    }
}
