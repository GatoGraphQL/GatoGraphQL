<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

use LogicException;
use PoP\Root\Facades\Registries\AppStateProviderRegistryFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

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

    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
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
            $appStateProvider->consolidate($this->state);
        }

        // Final pass: augment
        foreach ($appStateProviderRegistry->getAppStateProviders() as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }
    }

    public function all(): array
    {
        return $this->state;
    }

    /**
     * To be called by Engine. Use with care!
     */
    public function override(string $key, mixed $value): void
    {
        $this->state[$key] = $value;
    }

    /**
     * @throws LogicException
     */
    public function get(string $key): mixed
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        if (!isset($this->state[$key])) {
            throw new LogicException(
                \sprintf(
                    $translationAPI->__('There is no state under key \'%s\'', 'root'),
                    $key
                )
            );
        }
        return $this->state[$key] ?? null;
    }

    /**
     * @throws LogicException
     */
    public function getUnder(array $path): mixed
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if (!isset($state[$pathItem])) {
                throw new LogicException(
                    \sprintf(
                        $translationAPI->__('There is no state under path \'%s\'', 'root'),
                        implode(
                            $translationAPI->__(',', 'root'),
                            $path
                        )
                    )
                );
            }
            $state = &$state[$pathItem];
        }
        return $state;
    }

    public function has(string $key): bool
    {
        return isset($this->state[$key]);
    }

    /**
     * @throws LogicException
     */
    public function hasUnder(array $path): bool
    {
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if (!isset($state[$pathItem])) {
                return false;
            }
            $state = &$state[$pathItem];
        }
        return true;
    }
}
