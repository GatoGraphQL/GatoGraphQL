<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use PoP\Root\App;
use PoPAPI\APIClients\ClientTrait;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;

abstract class AbstractClient extends AbstractEndpointHandler
{
    use ClientTrait, WPClientTrait {
        WPClientTrait::getModuleBaseURL insteadof ClientTrait;
    }

    private ?TemplateHelpersInterface $templateHelpers = null;

    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        if ($this->templateHelpers === null) {
            /** @var TemplateHelpersInterface */
            $templateHelpers = $this->instanceManager->getInstance(TemplateHelpersInterface::class);
            $this->templateHelpers = $templateHelpers;
        }
        return $this->templateHelpers;
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
         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/710
         */
        \add_action(
            'init',
            function (): void {
                if ($this->isClientDisabled()) {
                    return;
                }
                parent::initialize();
            },
            0
        );
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

        /**
         * Add a Custom Header with the GraphQL endpoint to the response.
         *
         * Add it always (i.e. for both PROD and DEV) so that:
         *
         * - DEV: Can test that enabling/disabling the client works.
         * - PROD: Can execute the "PROD Integration Tests"
         * - In General: it's easier to find this useful information
         *               (the endpoint is also printed in the HTML)
         */
        $response->headers->set(CustomHeaders::CLIENT_ENDPOINT, $this->getEndpoint());

        // Add a hook to send the Response to the client.
        $this->getTemplateHelpers()->sendResponseToClient();
    }
}
