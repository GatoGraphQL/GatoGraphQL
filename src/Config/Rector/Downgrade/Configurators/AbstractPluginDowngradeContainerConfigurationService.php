<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use Rector\Config\RectorConfig;

abstract class AbstractPluginDowngradeContainerConfigurationService extends AbstractDowngradeContainerConfigurationService
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
