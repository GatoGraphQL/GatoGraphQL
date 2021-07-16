<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\EndpointAnnotatorInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractEndpointAnnotatorRegistry implements EndpointAnnotatorRegistryInterface
{
    /**
     * @var EndpointAnnotatorInterface[]
     */
    protected array $endpointAnnotators = [];

    public function addEndpointAnnotator(EndpointAnnotatorInterface $endpointAnnotator): void
    {
        $this->endpointAnnotators[] = $endpointAnnotator;
    }
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEndpointAnnotators(): array
    {
        // Only enabled services
        return array_filter(
            $this->endpointAnnotators,
            fn (ServiceInterface $service) => $service->isServiceEnabled()
        );
    }
}
