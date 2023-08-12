<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

interface BundleExtensionModuleResolverInterface extends ExtensionModuleResolverInterface
{
    /**
     * @return string[]
     */
    public function getBundledExtensionSlugs(string $module): array;
    /**
     * @return string[]
     */
    public function getGatoGraphQLBundledExtensionSlugs(string $module): array;
    /**
     * @return string[]
     */
    public function getGatoGraphQLBundledBundleExtensionSlugs(string $module): array;
}
