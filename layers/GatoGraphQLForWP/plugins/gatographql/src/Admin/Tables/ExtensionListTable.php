<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQLEngine\Admin\Tables\ExtensionListTable as UpstreamExtensionListTable;

/**
 * Extension Table implementation, which retrieves the Extensions
 * data pre-defined via ModuleResolvers
 */
class ExtensionListTable extends UpstreamExtensionListTable
{
    protected function isHighlightedBundle(string $bundleModule): bool
    {
        return (
            in_array($bundleModule, [
            BundleExtensionModuleResolver::PRO,
            BundleExtensionModuleResolver::ALL_EXTENSIONS,
            ])
        );
    }

    /**
     * Allow to change the title for extensions active via a bundle
     *
     * @param array<string,mixed> $plugin
     */
    public function getPluginInstallActionLabel(array $plugin): string
    {
        $displayGatoGraphQLPROBundleOnExtensionsPage = PluginStaticModuleConfiguration::displayGatoGraphQLPROBundleOnExtensionsPage();
        $displayGatoGraphQLPROFeatureBundlesOnExtensionsPage = PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage();

        $module = $plugin['gato_extension_module'];

        // If it's a Bundle => "Get Bundle", otherwise "Get Extension"
        if ($module === BundleExtensionModuleResolver::PRO) {
            $extensionActionLabel = sprintf(
                '%s%s',
                $displayGatoGraphQLPROBundleOnExtensionsPage && !$displayGatoGraphQLPROFeatureBundlesOnExtensionsPage ? sprintf('<strong>%s</strong>', \__('Go PRO', 'gatographql')) : \__('Get Bundle', 'gatographql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        } else {
            $extensionActionLabel = parent::getPluginInstallActionLabel($plugin);
        }

        return sprintf(
            '
                <span class="gatographql-extension-action-label">%s</span>
                <span class="gatographql-extension-bundle-action-label" style="display: none;">%s</span>
            ',
            $extensionActionLabel,
            $displayGatoGraphQLPROBundleOnExtensionsPage && !$displayGatoGraphQLPROFeatureBundlesOnExtensionsPage ? \__('Active (via PRO)', 'gatographql') : \__('Active (via Bundle)', 'gatographql')
        );
    }
}
