<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators;

class ExtensionDemoContainerConfigurationService extends AbstractPluginContainerConfigurationService
{
    protected function getPluginRelativePath(): string
    {
        return 'layers/GraphQLAPIForWP/plugins/extension-demo';
    }
}
