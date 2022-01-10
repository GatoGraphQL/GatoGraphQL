<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Services\ServiceInterface;

interface AppStateProviderInterface extends ServiceInterface
{
    /**
     * Have the Component set its own state, accessible for all Components in the App
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void;

    /**
     * Once all properties by all Components have been set,
     * have this second pass consolidate the state
     *
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void;
}
