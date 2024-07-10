<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;
use PoP\Root\Exception\AppStateNotExistsException;
use PoP\Root\Exception\ShouldNotHappenException;
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
     * Called by the AppLoader to initialize the state.
     *
     * Initialize application state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function initializeAppState(array $initialAppState): void
    {
        App::doAction(HookNames::BEFORE_INITIALIZING_APP_STATE);

        $this->state = [];
        $appStateProviderRegistry = AppStateProviderRegistryFacade::getInstance();
        $appStateProviders = $appStateProviderRegistry->getEnabledAppStateProviders();

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_BEFORE_INITIALIZED,
            $this->state
        );

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

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_INITIALIZED,
            $this->state
        );

        // Second pass: consolidate
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->consolidate($this->state);
        }

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_CONSOLIDATED,
            $this->state
        );

        // Third pass: augment
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->augment($this->state);
        }

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_AUGMENTED,
            $this->state
        );

        // Final pass: compute
        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->compute($this->state);
        }

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_COMPUTED,
            $this->state
        );

        App::doAction(HookNames::AFTER_INITIALIZING_APP_STATE);
    }

    /**
     * Called by the AppLoader to "boot" the state.
     *
     * Execute application state
     */
    public function executeAppState(): void
    {
        $appStateProviderRegistry = AppStateProviderRegistryFacade::getInstance();
        $appStateProviders = $appStateProviderRegistry->getEnabledAppStateProviders();

        foreach ($appStateProviders as $appStateProvider) {
            $appStateProvider->execute($this->state);
        }

        // Allow to inject state (eg: during tests on DEV)
        $this->state = App::applyFilters(
            HookNames::APP_STATE_EXECUTED,
            $this->state
        );
    }

    /**
     * Called by the AttachedGraphQLServer to backup the
     * state before executing a GraphQL request
     *
     * @return array<string,mixed>
     * @internal
     */
    public function getAppState(): array
    {
        return $this->state;
    }

    /**
     * Called by the AttachedGraphQLServer to restore the
     * state after executing a GraphQL request
     *
     * @param array<string,mixed> $appState
     * @internal
     */
    public function setAppState(array $appState): void
    {
        $this->state = $appState;
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
     * @param string[] $path
     */
    public function getUnder(array $path): mixed
    {
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if (!is_array($state) || !array_key_exists($pathItem, $state)) {
                $this->throwAppStateNotExistsException($path);
            }
            $state = &$state[$pathItem];
        }
        return $state;
    }

    /**
     * @throws ShouldNotHappenException
     */
    protected function assertIsSupportedSplObjectStorageItem(mixed $stateItem): void
    {
        if (!($stateItem instanceof FieldInterface)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->getTranslationAPI()->__('In the SplObjectStorage stored in the AppState, cannot process item of class \'%s\'', 'root'),
                    get_class($stateItem)
                )
            );
        }
    }

    /**
     * @throws AppStateNotExistsException If there is no state under the provided path
     * @param string[] $path
     */
    protected function throwAppStateNotExistsException(array $path): void
    {
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

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->state);
    }

    /**
     * @param string[] $path
     */
    public function hasUnder(array $path): bool
    {
        $state = &$this->state;
        foreach ($path as $pathItem) {
            if ($state === null) {
                // Iterating to a subentry that is not defined?
                return false;
            }
            if (!is_array($state) || !array_key_exists($pathItem, $state)) {
                return false;
            }
            $state = &$state[$pathItem];
        }
        return true;
    }
}
