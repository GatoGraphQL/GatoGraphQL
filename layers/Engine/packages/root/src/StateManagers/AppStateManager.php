<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\Root\Exception\AppStateNotExistsException;
use PoP\Root\Facades\Registries\AppStateProviderRegistryFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoP\Root\Translation\TranslationAPIInterface;

/**
 * Keep a reference to the global, shared state by the App
 */
class AppStateManager implements AppStateManagerInterface
{
    /**
     * The state, stored under their own property as added by each Module
     *
     * @var array<string,mixed>
     */
    protected array $state;

    final protected function getTranslationAPI(): TranslationAPIInterface
    {
        return TranslationAPIFacade::getInstance();
    }

    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function initializeAppState(array $initialAppState): void
    {
        $this->state = [];
        $appStateProviderRegistry = AppStateProviderRegistryFacade::getInstance();
        $appStateProviders = $appStateProviderRegistry->getEnabledAppStateProviders();

        // First pass: initialize
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->initialize($this->state);
        }

        /**
         * Only now override with the initial state.
         * Then we can set 'route' and 'nature' when passing state
         * via the request is disabled, as when running PHPUnit.
         */
        $this->state = array_merge(
            $this->state,
            $initialAppState
        );

        // Second pass: consolidate
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->consolidate($this->state);
        }

        // Third pass: augment
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }

        // Final pass: compute
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->compute($this->state);
        }
    }

    /**
     * @return array<string,mixed>
     */
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
     * @throws AppStateNotExistsException If there is no state under the provided key
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->state)) {
            throw new AppStateNotExistsException(
                \sprintf(
                    $this->getTranslationAPI()->__('There is no application state under key \'%s\'', 'root'),
                    $key
                )
            );
        }
        return $this->state[$key] ?? null;
    }

    /**
     * @throws AppStateNotExistsException If there is no state under the provided path
     */
    public function getUnder(array $path): mixed
    {
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if (!array_key_exists($pathItem, $state)) {
                throw new AppStateNotExistsException(
                    \sprintf(
                        $this->getTranslationAPI()->__('There is no application state under path \'%s\'', 'root'),
                        implode(
                            $this->getTranslationAPI()->__(',', 'root'),
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
        return array_key_exists($key, $this->state);
    }

    public function hasUnder(array $path): bool
    {
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if ($state === null) {
                // Iterating to a subentry that is not defined?
                return false;
            }
            if (!array_key_exists($pathItem, $state)) {
                return false;
            }
            $state = &$state[$pathItem];
        }
        return true;
    }
}
