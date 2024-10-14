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
     * These are the bundled Extension Bundles! For instance, the
     * "PRO" bundle bundles all other bundles.
     *
     * @return string[]
     */
    public function getBundledBundleExtensionSlugs(string $module): array;
    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array;
    /**
     * @return string[]
     */
    public function getBundledBundleExtensionModules(string $module): array;
    /**
     * @return string[]
     */
    public function getGatoGraphQLBundledExtensionSlugs(string $module): array;
    /**
     * @return string[]
     */
    public function getGatoGraphQLBundledBundleExtensionSlugs(string $module): array;
}
