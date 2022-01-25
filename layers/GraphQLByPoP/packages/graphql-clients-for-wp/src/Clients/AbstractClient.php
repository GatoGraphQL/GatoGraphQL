<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoPAPI\APIClients\ClientTrait;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;

abstract class AbstractClient extends AbstractEndpointHandler
{
    use ClientTrait, WPClientTrait {
        WPClientTrait::getComponentBaseURL insteadof ClientTrait;
        ClientTrait::executeEndpoint as upstreamExecuteEndpoint;
    }

    /**
     * Initialize the client
     */
    public function initialize(): void
    {
        /**
         * Subject to the endpoint having been defined.
         *
         * Execute on "init" to make sure that the CPT has been defined,
         * with priority 0 to execute before `initialize` on parent.
         *
         * Otherwise, if running `is_singular(...)` on service initialization,
         * it will throw an error:
         *
         *   Notice: is_singular was called <strong>incorrectly</strong>.
         *   Conditional query tags do not work before the query is run.
         *   Before then, they always return false.
         *
         * @see https://github.com/leoloso/PoP/issues/710
         */
        \add_action('init', function (): void {
            if (!$this->isClientDisabled()) {
                parent::initialize();
            }
        }, 0);
    }

    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        return false;
    }

    /**
     * Add a hook to send the Response to the client.
     */
    protected function executeEndpoint(): void
    {
        $this->upstreamExecuteEndpoint();
        $this->sendResponseToClient();
    }
}
