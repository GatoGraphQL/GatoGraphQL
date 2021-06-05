<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Skip tests
        '*/tests/*',
        '*/test/*',
        '*/Test/*',
    ]);
};
