<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use PoP\Root\State\AbstractAppStateProvider;

abstract class AbstractEndpointExecuterAppStateProvider extends AbstractAppStateProvider
{
    abstract protected function getEndpointExecuter(): EndpointExecuterInterface;

    public function isServiceEnabled(): bool
    {
        return $this->getEndpointExecuter()->isServiceEnabled();
    }
}
