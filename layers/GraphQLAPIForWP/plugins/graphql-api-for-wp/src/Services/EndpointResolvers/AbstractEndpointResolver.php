<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointResolvers;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractEndpointResolver extends AbstractAutomaticallyInstantiatedService
{
    protected ?EndpointHelpers $endpointHelpers = null;

    public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->getInstanceManager()->getInstance(EndpointHelpers::class);
    }

    //#[Required]
    final public function autowireAbstractEndpointResolver(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }

    /**
     * Initialize the resolver
     */
    public function initialize(): void
    {
        // Do nothing
    }
}
