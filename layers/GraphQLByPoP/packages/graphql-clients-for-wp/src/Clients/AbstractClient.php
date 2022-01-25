<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\Root\App;
use PoPAPI\APIClients\ClientTrait;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;

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
     * If the endpoint for the client is requested,
     * load the client's HTML code into the Response.
     */
    protected function executeEndpoint(): void
    {
        $response = App::getResponse();
        $response->setContent($this->getClientHTML());
        $response->headers->set('content-type', 'text/html');

        // Add a hook to send the Response to the client.
        $this->sendResponseToClient();
    }
}
