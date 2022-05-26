<?php

declare(strict_types=1);

use PoP\PoP\Config\Symplify\MonorepoBuilder\Configurators\ContainerConfigurationService;
use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
    $containerConfigurationService = new ContainerConfigurationService(
        $mbConfig,
        __DIR__
    );
    $containerConfigurationService->configureContainer();
};
