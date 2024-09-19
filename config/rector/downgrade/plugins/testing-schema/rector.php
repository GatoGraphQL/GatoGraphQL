<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\TestingSchemaContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new TestingSchemaContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 5)
    );
    $containerConfigurationService->configureContainer();
};
