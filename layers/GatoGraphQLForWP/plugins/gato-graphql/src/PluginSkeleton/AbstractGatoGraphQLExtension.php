<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

abstract class AbstractGatoGraphQLExtension extends AbstractExtension
{
    public function isCommercial(): bool
    {
        return true;
    }
}
