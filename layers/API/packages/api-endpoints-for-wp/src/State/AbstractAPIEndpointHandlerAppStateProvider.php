<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPAPI\API\Response\Schemes as APISchemes;
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
}
