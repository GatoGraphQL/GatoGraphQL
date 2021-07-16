<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLByPoP\GraphQLClientsForWP\Clients\AbstractClient;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractClientEndpointExecuter extends AbstractEndpointExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }

    public function executeEndpoint(): void
    {
        // Print the HTML from the client, and that's it
        echo $this->getClient()->getClientHTML();
        die;
    }

    abstract protected function getClient(): AbstractClient;
}
