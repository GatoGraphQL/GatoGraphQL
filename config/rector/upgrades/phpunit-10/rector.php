<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Upgrades\Configurators\PHPUnit10ContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new PHPUnit10ContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 4)
    );
    $containerConfigurationService->configureContainer();
};
