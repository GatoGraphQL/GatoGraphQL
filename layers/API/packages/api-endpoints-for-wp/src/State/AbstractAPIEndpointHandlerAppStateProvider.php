<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\APIEndpoints\EndpointHandlerInterface;

abstract class AbstractAPIEndpointHandlerAppStateProvider extends AbstractAppStateProvider
{
    abstract protected function getEndpointHandler(): EndpointHandlerInterface;

    public function isServiceEnabled(): bool
    {
        return $this->getEndpointHandler()->isEndpointRequested();
    }

    public function initialize(array &$state): void
    {
        $state['scheme'] = APISchemes::API;
    }

    public function consolidate(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }

        // Single endpoint, starting at the Root object
        $state['nature'] = RequestNature::QUERY_ROOT;
    }
}
