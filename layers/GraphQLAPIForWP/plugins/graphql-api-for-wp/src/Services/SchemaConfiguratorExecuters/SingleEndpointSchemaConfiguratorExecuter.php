<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SingleEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class SingleEndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected SingleEndpointSchemaConfigurator $endpointSchemaConfigurator
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    protected function getCustomPostID(): ?int
    {
        return 1;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->endpointSchemaConfigurator;
    }
}
