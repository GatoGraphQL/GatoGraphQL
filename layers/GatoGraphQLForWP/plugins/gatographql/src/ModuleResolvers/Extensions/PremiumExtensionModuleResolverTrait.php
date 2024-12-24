<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

trait PremiumExtensionModuleResolverTrait
{
    public function isPremium(string $module): bool
    {
        return true;
    }
}
