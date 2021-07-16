<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
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
    
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        if (!$this->isClientRequested()) {
            return false;
        }

        return true;
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType;
    }

    /**
     * Check the expected ?view=... is requested
     */
    protected function isClientRequested(): bool
    {
        return ($_REQUEST[RequestParams::VIEW] ?? null) === $this->getView();
    }

    abstract protected function getView(): string;

    public function executeEndpoint(): void
    {
        // Print the HTML from the client, and that's it
        echo $this->getClient()->getClientHTML();
        die;
    }

    abstract protected function getClient(): AbstractClient;
}
