<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\APIClients\ClientTrait;
use GraphQLByPoP\GraphQLClientsForWP\Clients\WPClientTrait;
use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;

abstract class AbstractClient extends AbstractEndpointHandler
{
    use ClientTrait, WPClientTrait {
        WPClientTrait::getComponentBaseURL insteadof ClientTrait;
    }

    /**
     * Initialize the client
     */
    public function initialize(): void
    {
        /**
         * Subject to the endpoint having been defined
         */
        if (!$this->isClientDisabled()) {
            parent::initialize();
        }
    }

    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        return false;
    }
}
