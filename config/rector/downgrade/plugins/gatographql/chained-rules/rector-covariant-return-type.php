<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\GatoGraphQLCovariantReturnTypeChainedRuleContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new GatoGraphQLCovariantReturnTypeChainedRuleContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 6)
    );
    $containerConfigurationService->configureContainer();
};
