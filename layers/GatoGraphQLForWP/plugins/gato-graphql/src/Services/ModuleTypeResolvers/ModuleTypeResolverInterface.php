<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers;

interface ModuleTypeResolverInterface
{
    /**
     * @return string[]
     */
    public function getModuleTypesToResolve(): array;
    public function getSlug(string $moduleType): string;
    public function getName(string $moduleType): string;
    /**
     * Used to categorize Extensions (to keep the code and logic
     * consistent, as this is not really needed)
     */
    public function isHidden(string $moduleType): bool;
}
