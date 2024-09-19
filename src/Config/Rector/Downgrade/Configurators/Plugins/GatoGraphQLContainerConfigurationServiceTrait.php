<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Rector\Downgrade\Configurators\Plugins;

trait GatoGraphQLContainerConfigurationServiceTrait
{
    protected function getPluginRelativePath(): string
    {
        return 'layers/GatoGraphQLForWP/plugins/gatographql';
    }
}
