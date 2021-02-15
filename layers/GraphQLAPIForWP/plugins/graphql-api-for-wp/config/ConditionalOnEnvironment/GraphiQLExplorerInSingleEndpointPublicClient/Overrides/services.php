<?php

declare(strict_types=1);

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use GraphQLByPoP\GraphQLClientsForWP\ConditionalOnEnvironment\UseGraphiQLExplorer\Overrides\Services\Clients\GraphiQLWithExplorerClient;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Override the GraphiQL clients
    $services->set(
        GraphiQLClient::class,
        GraphiQLWithExplorerClient::class
    );
};
