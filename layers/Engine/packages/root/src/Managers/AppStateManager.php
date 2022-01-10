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

    /**
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     * @throws LogicException
     */
    public function get(string|array $keyOrPath): mixed
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        if (is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            $stateValue = &$this->state;
            foreach ($path as $pathItem) {
                if (!isset($stateValue[$pathItem])) {
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
                $stateValue = &$stateValue[$pathItem];
            }
            return $stateValue;
        }
        /** @var string */
        $key = $keyOrPath;
        if (!isset($this->state[$key][$key])) {
            throw new LogicException(
                \sprintf(
                    $translationAPI->__('There is no state under key \'%s\'', 'root'),
                    $key
                )
            );
        }
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
            $appStateProvider->consolidate($this->state);
        }

        // Final pass: augment
        foreach ($appStateProviderRegistry->getAppStateProviders() as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }
    }
}
