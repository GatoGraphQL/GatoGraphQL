<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use PoP\Root\Services\ActivableServiceInterface;

interface SchemaConfiguratorInterface extends ActivableServiceInterface
{
    /**
     * Execute the schema configuration with certain ID
     */
    public function executeSchemaConfiguration(int $schemaConfigurationID): void;
    /**
     * Execute logic when no schema configuration was selected
     */
    public function executeNoneAppliedSchemaConfiguration(): void;
}
