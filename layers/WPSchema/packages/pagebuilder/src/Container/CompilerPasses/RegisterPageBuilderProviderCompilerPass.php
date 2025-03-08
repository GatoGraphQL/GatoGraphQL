<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\Container\CompilerPasses;

use PoPWPSchema\PageBuilder\Registries\PageBuilderProviderRegistryInterface;
use PoPWPSchema\PageBuilder\PageBuilderProviders\PageBuilderProviderInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterPageBuilderProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PageBuilderProviderRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PageBuilderProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addPageBuilderProvider';
    }
}
