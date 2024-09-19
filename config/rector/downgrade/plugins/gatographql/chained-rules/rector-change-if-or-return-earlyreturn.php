<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins\ChainedRules\GatoGraphQLChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new GatoGraphQLChangeIfOrReturnToEarlyReturnChainedRuleContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 6)
    );
    $containerConfigurationService->configureContainer();
};
