<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractSchemaConfigurationExecuterRegistry implements SchemaConfigurationExecuterRegistryInterface
{
    /**
     * @var SchemaConfigurationExecuterInterface[]
     */
    protected array $schemaConfigurationExecuters = [];

    public function addSchemaConfigurationExecuter(SchemaConfigurationExecuterInterface $schemaConfigurationExecuter): void
    {
        $this->schemaConfigurationExecuters[] = $schemaConfigurationExecuter;
    }
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getSchemaConfigurationExecuters(): array
    {
        return array_filter(
            $this->schemaConfigurationExecuters,
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
