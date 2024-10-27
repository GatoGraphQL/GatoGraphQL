<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\State;

use PoPAPI\APIEndpoints\EndpointHandlerInterface;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\NativeAPIEndpointHandler;
use PoPAPI\APIEndpointsForWP\State\AbstractAPIEndpointHandlerAppStateProvider;

class NativeAPIEndpointHandlerAppStateProvider extends AbstractAPIEndpointHandlerAppStateProvider
{
    private ?NativeAPIEndpointHandler $nativeAPIEndpointHandler = null;

    final protected function getNativeAPIEndpointHandler(): NativeAPIEndpointHandler
    {
        if ($this->nativeAPIEndpointHandler === null) {
            /** @var NativeAPIEndpointHandler */
            $nativeAPIEndpointHandler = $this->instanceManager->getInstance(NativeAPIEndpointHandler::class);
            $this->nativeAPIEndpointHandler = $nativeAPIEndpointHandler;
        }
        return $this->nativeAPIEndpointHandler;
    }

    protected function getEndpointHandler(): EndpointHandlerInterface
    {
        return $this->getNativeAPIEndpointHandler();
    }
}
