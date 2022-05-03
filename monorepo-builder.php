<?php

declare(strict_types=1);

use PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators\ContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new ContainerConfigurationService(
        $rectorConfig,
        __DIR__
    );
    $containerConfigurationService->configureContainer();
};
