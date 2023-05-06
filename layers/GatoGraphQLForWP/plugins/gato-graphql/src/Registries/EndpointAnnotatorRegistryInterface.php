<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators\EndpointAnnotatorInterface;

interface EndpointAnnotatorRegistryInterface
{
    public function addEndpointAnnotator(EndpointAnnotatorInterface $endpointAnnotator): void;
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEndpointAnnotators(): array;
    /**
     * @return EndpointAnnotatorInterface[]
     */
    public function getEnabledEndpointAnnotators(): array;
}
