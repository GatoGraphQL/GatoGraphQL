<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\Clients;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient as UpstreamGraphiQLClient;

/**
 * Supplies the graphiql-app build base URL from the plugin's vendor path
 * so the public single endpoint at /graphiql/ serves GraphiQL v5.
 */
class GraphiQLClient extends UpstreamGraphiQLClient
{
    protected function getGraphiQLAppBuildBaseURL(): ?string
    {
        $mainPlugin = PluginApp::getMainPlugin();
        return $mainPlugin->getPluginURL()
            . 'vendor/graphql-by-pop/graphql-clients-for-wp/clients/graphiql-app/build/';
    }
}
