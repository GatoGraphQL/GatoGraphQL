<?php

declare(strict_types=1);

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\ConditionalOnEnvironment\GraphiQLExplorerInAdminClient\Overrides\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\GraphiQLMenuPage as UpstreamGraphiQLMenuPage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Override the Menu Page
    $services->set(
        UpstreamGraphiQLMenuPage::class,
        GraphiQLMenuPage::class
    );
};
