<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

abstract class AbstractRegisterEndpointAnnotatorCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryMethodCallName(): string
    {
        return 'addEndpointAnnotator';
    }
}
