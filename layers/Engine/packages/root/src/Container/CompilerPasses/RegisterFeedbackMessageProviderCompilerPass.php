<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
use PoP\Root\Registries\FeedbackMessageRegistryInterface;
use PoP\Root\FeedbackMessageProviders\FeedbackMessageProviderInterface;

class RegisterFeedbackMessageProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return FeedbackMessageRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return FeedbackMessageProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'useFeedbackMessageProvider';
    }
}
