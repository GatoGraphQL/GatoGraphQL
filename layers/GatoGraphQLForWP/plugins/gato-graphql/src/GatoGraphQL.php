<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

final class GatoGraphQL
{
    public static function getAdminEndpoint(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminGraphQLEndpoint();
    }

    public static function getAdminBlockEditorEndpoint(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminBlockEditorGraphQLEndpoint();
    }

    public static function getAdminCustomEndpoint(string $endpointGroup): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        return $endpointHelpers->getAdminGraphQLEndpoint($endpointGroup);
    }
}
