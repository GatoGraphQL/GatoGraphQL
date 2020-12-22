<?php

declare(strict_types=1);

namespace PoP\HooksWP;

class HooksAPI implements \PoP\Hooks\HooksAPIInterface
{
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        \add_filter($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return \remove_filter($tag, $function_to_remove, $priority);
    }
    /**
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    public function applyFilters(string $tag, $value, ...$args)
    {
        return \apply_filters($tag, $value, ...$args);
    }
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        \add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return \remove_action($tag, $function_to_remove, $priority);
    }
    /**
     * @param mixed ...$args
     */
    public function doAction(string $tag, ...$args): void
    {
        \do_action($tag, ...$args);
    }
}
