<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\TestingSchemaContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new TestingSchemaContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 4)
    );
    $containerConfigurationService->configureContainer();
};
