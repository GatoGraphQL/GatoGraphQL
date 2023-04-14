<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\StateManagers;

use GraphQLAPI\GraphQLAPI\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\StateManagers\HookManagerInterface;

/**
 * Wrap the HookManager to add the current AppThread hash
 * as part of every hook name.
 *
 * This way, hooks initialized for the Standard and Internal
 * GraphQL servers will not conflict with each other.
 */
class AppThreadHookManagerWrapper implements HookManagerInterface
{
    private string $appThreadName;

    public function __construct(
        private HookManagerInterface $hookManager,
    ) {
        $currentAppThreadName = App::getAppThread()->getName();
        if ($currentAppThreadName === null) {
            throw new ShouldNotHappenException(
                \__('AppThread has no name', 'graphql-api')
            );
        }
        $this->appThreadName = $currentAppThreadName;
    }

    private function getAppThreadTag(string $tag): string
    {
        return sprintf(
            'AppThread:%s-%s',
            $this->appThreadName,
            $tag
        );
    }

    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->hookManager->addFilter($this->getAppThreadTag($tag), $function_to_add, $priority, $accepted_args);
    }
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->hookManager->removeFilter($this->getAppThreadTag($tag), $function_to_remove, $priority);
    }
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return $this->hookManager->applyFilters($this->getAppThreadTag($tag), $value, ...$args);
    }
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->hookManager->addAction($this->getAppThreadTag($tag), $function_to_add, $priority, $accepted_args);
    }
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->hookManager->removeAction($this->getAppThreadTag($tag), $function_to_remove, $priority);
    }
    public function doAction(string $tag, mixed ...$args): void
    {
        $this->hookManager->doAction($this->getAppThreadTag($tag), ...$args);
    }
}
