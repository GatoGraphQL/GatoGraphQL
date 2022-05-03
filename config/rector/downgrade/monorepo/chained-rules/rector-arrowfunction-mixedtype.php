<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\MonorepoArrowFnMixedTypeChainedRuleContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new MonorepoArrowFnMixedTypeChainedRuleContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 5)
    );
    $containerConfigurationService->configureContainer();
};
