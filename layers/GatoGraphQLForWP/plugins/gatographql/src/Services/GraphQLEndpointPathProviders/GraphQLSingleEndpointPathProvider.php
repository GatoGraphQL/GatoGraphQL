<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;

class GraphQLSingleEndpointPathProvider extends AbstractGraphQLEndpointPathProvider
{
    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT;
    }

    public function getPath(): string
    {
        return $this->getUserSettingsManager()->getSetting(
            EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
            ModuleSettingOptions::PATH
        );
    }

    public function isPublic(): bool
    {
        return true;
    }
}
