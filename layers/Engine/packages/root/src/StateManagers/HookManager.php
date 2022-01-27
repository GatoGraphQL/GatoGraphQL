<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoPBackbone\PHPHooks\PHPHooks;

class HookManager implements HookManagerInterface
{
    protected PHPHooks $phpHooks;

    public function __construct()
    {
        $this->phpHooks = new PHPHooks();
        /**
         * Copied from bainternet/php-hooks/php-hooks.php
         *
         * @see https://github.com/bainternet/PHP-Hooks/blob/7b28d10ed7a2f7e3c8bd7f53ba1e9b4769955242/php-hooks.php#L562
         */
        $this->phpHooks->do_action('After_Hooks_Setup', $this->phpHooks);
    }

    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->phpHooks->add_filter($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->phpHooks->remove_filter($tag, $function_to_remove, $priority);
    }
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return $this->phpHooks->apply_filters($tag, $value, ...$args);
    }
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->phpHooks->add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->phpHooks->remove_action($tag, $function_to_remove, $priority);
    }
    public function doAction(string $tag, mixed ...$args): void
    {
        $this->phpHooks->do_action($tag, ...$args);
    }
}
