<?php

declare(strict_types=1);

namespace PoP\HooksPHP;

use Hooks;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\App;

class HooksAPI implements HooksAPIInterface
{
    /**
     * Store the instance in the App as a runtime service.
     * It must be lazy load, since the state will be deleted when
     * calling App::initialize() in the tests.
     */
    protected function getInstance(): Hooks
    {
        if (!isset(App::$runtimeServices['hooks'])) {
            App::$runtimeServices['hooks'] = new Hooks();
        }
        return App::$runtimeServices['hooks'];
    }

    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $hooks = $this->getInstance();
        $hooks->add_filter($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        $hooks = $this->getInstance();
        return $hooks->remove_filter($tag, $function_to_remove, $priority);
    }
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        $hooks = $this->getInstance();
        return $hooks->apply_filters($tag, $value, ...$args);
    }
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $hooks = $this->getInstance();
        $hooks->add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        $hooks = $this->getInstance();
        return $hooks->remove_action($tag, $function_to_remove, $priority);
    }
    public function doAction(string $tag, mixed ...$args): void
    {
        $hooks = $this->getInstance();
        $hooks->do_action($tag, ...$args);
    }
}
