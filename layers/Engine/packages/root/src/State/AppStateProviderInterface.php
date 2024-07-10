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

    /**
     * After services have been initialized, we can then "boot" the AppState.
     *
     * Eg: parsing the GraphQL query must be done on this stage,
     * as to allow the SchemaConfigurationExecuter to inject its hooks
     * (eg: Composable Directives enabled?)  in the `boot` stage.
     * Otherwise, the env var would be read before it was properly initialized.
     *
     * @param array<string,mixed> $state
     */
    public function execute(array &$state): void;
}
