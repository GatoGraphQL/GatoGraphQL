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
}
