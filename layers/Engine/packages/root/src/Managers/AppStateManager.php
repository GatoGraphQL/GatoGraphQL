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

    public function set(string $key, mixed $value): void
    {
        $this->state[$key] = $value;
    }

    public function merge(string $key, array $value): void
    {
        $this->state[$key] ??= [];
        $this->state[$key] = array_merge(
            $this->state[$key],
            $value
        );
    }

    /**
     * @param string $keyOrPath The property key
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
     * @param string[] $keyOrPath The property path under which to retrieve a value
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
            $appStateProvider->consolidate($this->state);
        }

        // Final pass: augment
        foreach ($appStateProviderRegistry->getAppStateProviders() as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }
    }
}
