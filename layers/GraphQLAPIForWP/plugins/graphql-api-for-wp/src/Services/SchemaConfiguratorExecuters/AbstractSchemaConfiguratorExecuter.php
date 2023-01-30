<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoP\Root\Module\ApplicationEvents;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractSchemaConfiguratorExecuter extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    /**
     * Execute before all the services are attached,
     * as to use this configuration to affect these.
     *
     * For instance, the Queryable Custom Post Types can be
     * configured in the Schema Configuration, and from this list
     * will the ObjectTypeResolverPicker for the GenericCustomPost
     * decide if to add it to the CustomPostUnion or not. Hence,
     * this service must be executed before the Attachable services
     * are executed.
     */
    public function getInstantiationEvent(): string
    {
        return ApplicationEvents::PRE_BOOT;
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
