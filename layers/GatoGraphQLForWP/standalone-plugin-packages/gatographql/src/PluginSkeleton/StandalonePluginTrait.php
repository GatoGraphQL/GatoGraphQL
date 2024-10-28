<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

trait StandalonePluginTrait
{
    protected function getGatoGraphQLComposerRelativePath(): string
    {
        return 'vendor/gatographql/gatographql';
    }
}
