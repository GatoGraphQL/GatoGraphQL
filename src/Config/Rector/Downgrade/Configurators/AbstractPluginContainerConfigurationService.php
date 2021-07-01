<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

abstract class AbstractPluginContainerConfigurationService extends AbstractContainerConfigurationService
{
    protected string $pluginDir;

    public function __construct(
        ContainerConfigurator $containerConfigurator,
        string $rootDirectory,
    ) {
        parent::__construct($containerConfigurator, $rootDirectory);

        $this->pluginDir = $this->rootDirectory . '/' . $this->getPluginRelativePath();
    }

    abstract protected function getPluginRelativePath(): string;
}
