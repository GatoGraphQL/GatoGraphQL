<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\State;

use PoPAPI\APIEndpoints\EndpointHandlerInterface;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\NativeAPIEndpointHandler;
use PoPAPI\APIEndpointsForWP\State\AbstractAPIEndpointHandlerAppStateProvider;

class NativeAPIEndpointHandlerAppStateProvider extends AbstractAPIEndpointHandlerAppStateProvider
{
    private ?NativeAPIEndpointHandler $nativeAPIEndpointHandler = null;
    
    final public function setNativeAPIEndpointHandler(NativeAPIEndpointHandler $nativeAPIEndpointHandler): void
    {
        $this->nativeAPIEndpointHandler = $nativeAPIEndpointHandler;
    }
    final protected function getNativeAPIEndpointHandler(): NativeAPIEndpointHandler
    {
        return $this->nativeAPIEndpointHandler ??= $this->instanceManager->getInstance(NativeAPIEndpointHandler::class);
    }

    protected function getEndpointHandler(): EndpointHandlerInterface
    {
        return $this->getNativeAPIEndpointHandler();
    }
}
