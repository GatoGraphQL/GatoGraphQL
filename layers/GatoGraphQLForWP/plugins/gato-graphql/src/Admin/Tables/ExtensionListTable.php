<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use PHP_CodeSniffer\Generators\HTML;

/**
 * Extension Table implementation, which retrieves the Extensions
 * data pre-defined via ModuleResolvers
 */
class ExtensionListTable extends AbstractExtensionListTable
{
    use WithOpeningModuleDocInModalListTableTrait;

    public function overridePluginsAPIResult(): mixed
    {
        $plugins = $this->getAllItems();
        return (object) [
            'info' => [
                'page' => 1,
                'pages' => 1,
                'results' => count($plugins),
            ],
            'plugins' => $plugins,
        ];
    }

    /**
     * Retrieve all the Extensions from the Registry, and
     * generate an array with the data in the expected format
     * by the upstream WordPress class.
     *
     * @return mixed[]
     */
    protected function getAllItems(): array
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $isBundleExtension = $moduleResolver instanceof BundleExtensionModuleResolverInterface;
            $item = [
                'name' => $moduleResolver->getName($module),
                'slug' => $moduleResolver->getGatoGraphQLExtensionSlug($module),
                'short_description' => $moduleResolver->getDescription($module),
                'homepage' => $moduleResolver->getWebsiteURL($module),
                'icons' => [
                    'default' => $moduleResolver->getLogoURL($module),
                ],

                /**
                 * These are custom properties, not required by the upstream class,
                 * but used internally to modify the generated HTML content
                 */
                'gato_extension_module' => $module,
                'gato_extension_is_bundle' => $isBundleExtension,
            ];
            if ($isBundleExtension) {
                /** @var BundleExtensionModuleResolverInterface */
                $bundleExtensionModuleResolver = $moduleResolver;
                $item['gato_extension_bundled_extension_slugs'] = $bundleExtensionModuleResolver->getGatoGraphQLBundledExtensionSlugs($module);
            }
            $items[] = $item;
        }
        return $this->combineExtensionItemsWithCommonPluginData($items);
    }

    /**
     * @param array<string,mixed> $plugin
     */
    public function getPluginInstallActionLabel(array $plugin): string
    {
        if ($plugin['gato_extension_is_bundle']) {
            if ($plugin['gato_extension_module'] === BundleExtensionModuleResolver::ALL_EXTENSIONS) {
                return sprintf(
                    '%s%s',
                    \__('Join the Gato Club', 'gato-graphql'),
                    HTMLCodes::OPEN_IN_NEW_WINDOW
                );
            }
            return sprintf(
                '%s%s',
                \__('Get Bundle', 'gato-graphql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        /**
         * Allow to change the title for extensions active via a bundle
         */
        $extensionActionLabel = parent::getPluginInstallActionLabel($plugin);
        return sprintf(
            <<<HTML
                <span class="gato-graphql-extension-action-label">%s</span>
                <span class="gato-graphql-extension-bundle-action-label" style="display: none;">%s</span>
            HTML,
            $extensionActionLabel,
            \__('Active (via Bundle)', 'gato-graphql')
        );
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdditionalPluginCardClassnames(array $plugin): ?string
    {
        if ($plugin['gato_extension_is_bundle']) {
            $additionalPluginCardClassnames = 'plugin-card-extension-bundle';
            if ($plugin['gato_extension_module'] === BundleExtensionModuleResolver::ALL_EXTENSIONS) {
                $additionalPluginCardClassnames .= ' plugin-card-highlight';
            }
            return $additionalPluginCardClassnames;
        }
        return parent::getAdditionalPluginCardClassnames($plugin);
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdaptedDetailsLink(array $plugin): string
    {
        /**
         * This is a custom property, not required by the upstream class,
         * but used internally to modify the generated HTML content
         *
         * @var string
         */
        $extensionModule = $plugin['gato_extension_module'];
        return $this->getOpeningModuleDocInModalLinkURL(
            App::request('page') ?? App::query('page', ''),
            $extensionModule,
        );
    }

    /**
     * Gets a list of CSS classes for the WP_List_Table table tag.
     *
     * @since 3.1.0
     *
     * @return string[] Array of CSS classes for the table tag.
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_table_classes()
    {
        return array_merge(
            parent::get_table_classes(),
            [
                'gato-graphql-list-table',
            ]
        );
    }

    /**
     * Add a class to the bundled extensions
     */
    protected function adaptDisplayRowsHTML(string $html): string
    {
        $html = parent::adaptDisplayRowsHTML($html);

        /**
         * @see wp-admin/includes/class-wp-plugin-install-list-table.php
         */
        $activeButtonHTML = sprintf(
            '<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
            _x('Active', 'plugin')
        );

        foreach ((array) $this->items as $plugin) {
            // Check it is a Bundle Extension
            if (!$plugin['gato_extension_is_bundle']) {
                continue;
            }

            // Check it is active
            $pluginName = $plugin['name'];
            $actionLinks = $this->pluginActionLinks[$pluginName] ?? [];
            if (($actionLinks[0] ?? '') !== $activeButtonHTML) {
                continue;
            }

            /**
             * Replace classname "plugin-card-non-installed" with
             * "plugin-card-bundler-active" in the bundled extensions.
             *
             * @var string[]
             */
            $bundledExtensionSlugs = $plugin['gato_extension_bundled_extension_slugs'];
            foreach ($bundledExtensionSlugs as $bundledExtensionSlug) {
                $pluginCardClassname = 'plugin-card-' . sanitize_html_class($bundledExtensionSlug);
                $pos = strpos($html, $pluginCardClassname . ' plugin-card-non-installed');
                if ($pos !== false) {
                    $html = substr_replace($html, $pluginCardClassname . ' plugin-card-bundler-active', $pos, strlen($pluginCardClassname . ' plugin-card-non-installed'));
                }
            }
        }

        return $html;
    }
}
