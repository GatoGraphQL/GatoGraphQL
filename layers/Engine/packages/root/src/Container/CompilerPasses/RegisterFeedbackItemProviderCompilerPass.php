<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
use PoP\Root\Registries\FeedbackItemRegistryInterface;
use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;

class RegisterFeedbackItemProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return FeedbackItemRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return FeedbackItemProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'useFeedbackItemProvider';
    }
}
