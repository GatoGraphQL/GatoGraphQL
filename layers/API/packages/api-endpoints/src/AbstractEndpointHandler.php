<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpoints;

use PoP\Root\Routing\RoutingHelperServiceInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractEndpointHandler extends AbstractAutomaticallyInstantiatedService implements EndpointHandlerInterface
{
    use BasicServiceTrait;

    protected ?string $endpoint = null;

    private ?RoutingHelperServiceInterface $routingHelperService = null;

    final public function setRoutingHelperService(RoutingHelperServiceInterface $routingHelperService): void
    {
        $this->routingHelperService = $routingHelperService;
    }
    final protected function getRoutingHelperService(): RoutingHelperServiceInterface
    {
        if ($this->routingHelperService === null) {
            /** @var RoutingHelperServiceInterface */
            $routingHelperService = $this->instanceManager->getInstance(RoutingHelperServiceInterface::class);
            $this->routingHelperService = $routingHelperService;
        }
        return $this->routingHelperService;
    }

    /**
     * Initialize the client
     */
    public function initialize(): void
    {
        /**
         * Subject to the endpoint having been defined
         */
        if ($this->endpoint = $this->getEndpoint()) {
            // Make sure the endpoint has trailing "/" on both ends
            $this->endpoint = EndpointUtils::slashURI($this->endpoint);
        }
    }

    /**
     * If `true`, the endpoint must exactly match the URL
     * If `false`, the endpoint is triggered when it is contained at the end of the URL
     */
    protected function doesEndpointMatchWholeURL(): bool
    {
        return true;
    }

    /**
     * Get the requested URI to compare it against the endpoint
     */
    protected function getRequestedURI(): string
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $requestURI = $this->getRoutingHelperService()->getRequestURI() ?? '';
        $uri = EndpointUtils::removeMarkersFromURI($requestURI);
        // Same as the endpoint, make sure the URI has "/" in both ends
        return EndpointUtils::slashURI($uri);
    }

    /**
     * Indicate if the endpoint has been requested
     */
    public function isEndpointRequested(): bool
    {
        /**
         * For static analysis
         */
        if ($this->endpoint === null) {
            return false;
        }
        // Compare the formatted requested URI against the endpoint
        $uri = $this->getRequestedURI();
        if ($this->doesEndpointMatchWholeURL()) {
            return $uri === $this->endpoint;
        }
        return EndpointUtils::doesURIEndWith($uri, $this->endpoint);
    }
}
