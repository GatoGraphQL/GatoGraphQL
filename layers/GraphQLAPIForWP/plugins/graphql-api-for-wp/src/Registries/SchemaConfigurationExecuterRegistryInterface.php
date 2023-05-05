<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface;

interface SchemaConfigurationExecuterRegistryInterface
{
    public function addSchemaConfigurationExecuter(SchemaConfigurationExecuterInterface $schemaConfigurationExecuter): void;
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getSchemaConfigurationExecuters(): array;
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getEnabledSchemaConfigurationExecuters(): array;
}
