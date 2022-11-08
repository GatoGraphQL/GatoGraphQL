<?php

declare(strict_types=1);

use PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules\GraphQLAPICovariantChainedRuleContainerConfigurationService;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurationService = new GraphQLAPICovariantChainedRuleContainerConfigurationService(
        $rectorConfig,
        dirname(__DIR__, 5)
    );
    $containerConfigurationService->configureContainer();
};
