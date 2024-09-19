<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\TestingContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new TestingContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 4)
    );
    $containerConfigurationService->configureContainer();
};
