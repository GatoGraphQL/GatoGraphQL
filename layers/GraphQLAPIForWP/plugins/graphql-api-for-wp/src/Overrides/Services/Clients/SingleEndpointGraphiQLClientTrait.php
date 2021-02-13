<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\Services\Clients;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;

trait SingleEndpointGraphiQLClientTrait
{
    /**
     * Use GraphiQL Explorer for this screen?
     */
    protected function isGraphiQLExplorerOptionEnabled(): bool
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        return $userSettingsManager->getSetting(
            ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
            ClientFunctionalityModuleResolver::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT
        );
    }
}
