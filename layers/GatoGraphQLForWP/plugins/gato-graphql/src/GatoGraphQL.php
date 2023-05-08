<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class GatoGraphQL
{
    public function getAdminEndpoint(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminGraphQLEndpoint();
    }

    public function getAdminBlockEditorEndpoint(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminBlockEditorGraphQLEndpoint();
    }

    public function getAdminCustomEndpoint(string $endpointGroup): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminGraphQLEndpoint($endpointGroup);
    }
}
