<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\ChainedRules;

use Rector\Config\RectorConfig;

abstract class AbstractPluginArrowFnUnionTypeChainedRuleContainerConfigurationService extends AbstractArrowFnUnionTypeChainedRuleContainerConfigurationService
{
    protected string $pluginDir;

    public function __construct(
        RectorConfig $rectorConfig,
        string $rootDirectory,
    ) {
        parent::__construct($rectorConfig, $rootDirectory);

        $this->pluginDir = $this->rootDirectory . '/' . $this->getPluginRelativePath();
    }

    abstract protected function getPluginRelativePath(): string;
}
