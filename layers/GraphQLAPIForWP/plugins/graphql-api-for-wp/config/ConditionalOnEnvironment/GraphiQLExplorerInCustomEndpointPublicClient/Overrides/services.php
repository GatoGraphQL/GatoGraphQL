<?php

declare(strict_types=1);

use GraphQLAPI\GraphQLAPI\Clients\CustomEndpointGraphiQLClient;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInCustomEndpointPublicClient\Overrides\Services\Clients\CustomEndpointGraphiQLWithExplorerClient;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Override the GraphiQL clients
    $services->set(
        CustomEndpointGraphiQLClient::class,
        CustomEndpointGraphiQLWithExplorerClient::class
    );
};
