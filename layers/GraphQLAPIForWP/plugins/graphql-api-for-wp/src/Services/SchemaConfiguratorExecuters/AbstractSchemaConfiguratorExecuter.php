<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Component\ApplicationEvents;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractSchemaConfiguratorExecuter extends AbstractAutomaticallyInstantiatedService
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractSchemaConfiguratorExecuter(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }

    public function getInstantiationEvent(): string
    {
        return ApplicationEvents::BOOT;
    }

    /**
     * Initialize the configuration if a certain condition is satisfied
     */
    public function initialize(): void
    {
        if ($customPostID = $this->getCustomPostID()) {
            $schemaConfigurator = $this->getSchemaConfigurator();
            $schemaConfigurator->executeSchemaConfiguration($customPostID);
        }
    }

    /**
     * Provide the ID of the custom post containing the Schema Configuration block
     */
    abstract protected function getCustomPostID(): ?int;

    /**
     * Initialize the configuration of services before the execution of the GraphQL query
     */
    abstract protected function getSchemaConfigurator(): SchemaConfiguratorInterface;
}
