<?php

declare(strict_types=1);

namespace PoP\Root\StateManagers;

use LogicException;

interface AppStateManagerInterface
{
    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function initializeAppState(array $initialAppState): void;

    /**
     * @return array<string,mixed>
     */
    public function all(): array;

    /**
     * To be called by Engine. Use with care!
     */
    public function override(string $key, mixed $value): void;

    /**
     * @throws LogicException If there is no state under the provided key
     */
    public function get(string $key): mixed;

    /**
     * @throws LogicException If there is no state under the provided path
     */
    public function getUnder(array $path): mixed;

    public function has(string $key): bool;

    public function hasUnder(array $path): bool;
}
