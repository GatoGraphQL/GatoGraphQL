<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient as UpstreamGraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLWithExplorerClient as UpstreamGraphiQLWithExplorerClient;
use GraphQLAPI\GraphQLAPI\Overrides\Services\Clients\GraphiQLClient;
use GraphQLAPI\GraphQLAPI\Overrides\Services\Clients\GraphiQLWithExplorerClient;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Override the GraphiQL clients
    $services->set(
        UpstreamGraphiQLClient::class,
        GraphiQLClient::class
    );
    $services->set(
        UpstreamGraphiQLWithExplorerClient::class,
        GraphiQLWithExplorerClient::class
    );
};
