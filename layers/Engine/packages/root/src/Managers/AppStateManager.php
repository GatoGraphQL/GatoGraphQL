<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

use PoP\Root\Facades\Registries\AppStateProviderRegistryFacade;

/**
 * Keep a reference to the global, shared state by the App
 */
class AppStateManager
{
    /**
     * The state, stored under their own property as added by each Component
     *
     * @var array<string,mixed>
     */
    protected array $state;

    public function set(string $key, mixed $value): void
    {
        $this->state[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->state[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->state[$key]);
    }

    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
     *
     * @param array<string,mixed> $state
     */
    public function initializeAppState(): void
    {
        $this->state = [];
        $appStateProviderRegistry = AppStateProviderRegistryFacade::getInstance();

        // First pass: initialize
        foreach ($appStateProviderRegistry->getAppStateProviders() as $appStateProvider) {
            $appStateProvider->initialize($this->state);
        }

        // Second pass: consolidate
        foreach ($appStateProviderRegistry->getAppStateProviders() as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }
    }
}
