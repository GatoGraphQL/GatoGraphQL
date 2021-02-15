<?php

declare(strict_types=1);

use GraphQLByPoP\GraphQLClientsForWP\Clients\GraphiQLClient;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Make sure the GraphiQL client is used, without the Explorer
    // Because if isGraphiQLExplorerEnabled might be true, the explorer is enabled
    // but if disabled for the single endpoint, then it must not
    // (for that case, it will be overriden once again by another ConditionalOnEnvironment)
    $services->set(
        GraphiQLClient::class,
        GraphiQLClient::class
    );
};
