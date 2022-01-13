<?php

declare(strict_types=1);

namespace PoP\Root\Managers;

use LogicException;

interface AppStateManagerInterface
{
    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
     */
    public function initializeAppState(): void;

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
