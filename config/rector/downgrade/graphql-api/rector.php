<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\GraphQLAPIContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new GraphQLAPIContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 4)
    );
    $containerConfigurationService->configureContainer();
};
