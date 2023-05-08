<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

final class GatoGraphQL
{
    private static ?EndpointHelpers $endpointHelpers = null;

    final static private function getEndpointHelpers(): EndpointHelpers
    {
        if (self::$endpointHelpers === null) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            self::$endpointHelpers = $instanceManager->getInstance(EndpointHelpers::class);
        }
        return self::$endpointHelpers;
    }

    public static function getAdminEndpoint(): string
    {
        return static::getEndpointHelpers()->getAdminGraphQLEndpoint();
    }

    public static function getAdminBlockEditorEndpoint(): string
    {
        return static::getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint();
    }

    public static function getAdminCustomEndpoint(string $endpointGroup): string
    {
        return static::getEndpointHelpers()->getAdminGraphQLEndpoint($endpointGroup);
    }
}
