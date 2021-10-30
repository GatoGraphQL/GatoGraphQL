<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractEndpointResolver extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;

    private ?EndpointHelpers $endpointHelpers = null;

    public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    /**
     * Initialize the resolver
     */
    public function initialize(): void
    {
        // Do nothing
    }
}
