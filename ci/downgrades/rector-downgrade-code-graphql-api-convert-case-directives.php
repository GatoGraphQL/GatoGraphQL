<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    $monorepoDir = dirname(__DIR__, 2);
    $pluginDir = $monorepoDir . '/layers/GraphQLAPIForWP/plugins/events-manager';

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Skip tests
        '*/tests/*',

        // Skip since they are not needed and they fail
        $pluginDir . '/vendor/composer/*',
    ]);
};
