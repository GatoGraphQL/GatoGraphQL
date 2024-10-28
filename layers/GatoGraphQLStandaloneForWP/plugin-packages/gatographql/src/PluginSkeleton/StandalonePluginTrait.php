<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

trait StandalonePluginTrait
{
    protected function getGatoGraphQLComposerRelativePath(): string
    {
        return 'vendor/gatographql/gatographql';
    }
}
