<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\GatoGraphQLContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new GatoGraphQLContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 5)
    );
    $containerConfigurationService->configureContainer();
};
