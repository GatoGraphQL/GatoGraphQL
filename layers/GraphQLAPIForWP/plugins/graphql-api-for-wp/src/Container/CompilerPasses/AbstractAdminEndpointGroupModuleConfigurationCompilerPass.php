<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\EndpointConfiguration\AdminEndpointModuleConfigurationStoreInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractAdminEndpointGroupModuleConfigurationCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $adminEndpointModuleConfigurationStore = $containerBuilderWrapper->getDefinition(AdminEndpointModuleConfigurationStoreInterface::class);
        $adminEndpointModuleConfigurationStore->addMethodCall(
            'addEndpointGroupModuleClassConfiguration',
            [
                $this->getEndpointGroup(),
                $this->getModuleClassConfiguration()
            ]
        );
    }

    /**
     * @return array<string,array<string,mixed>>
     * @phpstan-return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    abstract protected function getModuleClassConfiguration(): array;
    abstract protected function getEndpointGroup(): string;
}
