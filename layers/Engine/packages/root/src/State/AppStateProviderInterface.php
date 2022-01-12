<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Services\ServiceInterface;

interface AppStateProviderInterface extends ServiceInterface
{
    /**
     * Initialize some state in the application
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void;

    /**
     * Modify properties possibly set by other packages
     *
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void;

    /**
     * Further modify the properties
     *
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void;

    /**
     * Once all properties have been set,
     * have a final pass add derivative properties
     *
     * @param array<string,mixed> $state
     */
    public function compute(array &$state): void;
}
