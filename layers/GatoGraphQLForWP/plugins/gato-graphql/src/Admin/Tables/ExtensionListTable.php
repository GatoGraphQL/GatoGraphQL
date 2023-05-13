<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginApp;

use function get_plugin_data;

/**
 * Extension Table implementation, which retrieves the Extensions
 * data pre-defined via ModuleResolvers
 */
class ExtensionListTable extends AbstractExtensionListTable
{
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
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginVersion = $mainPlugin->getPluginVersion();
        $pluginURL = $mainPlugin->getPluginURL();
        $gatoGraphQLLogoFile = $pluginURL . 'assets-pro/img/GatoGraphQL-logo.svg';

        /**
         * Retrieve the plugin data for the Gato GraphQL plugin.
         * As all extensions live in the same monorepo, they have
         * the same requirements.
         *
         * @see https://developer.wordpress.org/reference/functions/get_plugin_data/
         */
        $gatoGraphQLPluginData = get_plugin_data($mainPlugin->getPluginFile());

        $commonPluginData = [
            'version' => $mainPluginVersion,
            'author' => sprintf(
                '<a href="%s">%s</a>',
                $gatoGraphQLPluginData['AuthorURI'],
                $gatoGraphQLPluginData['Author']
            ),
            'requires' => $gatoGraphQLPluginData['RequiresWP'],
            'requires_php' => $gatoGraphQLPluginData['RequiresPHP'],
            'icons' => [
                'svg' => $gatoGraphQLLogoFile,
                '1x' => $gatoGraphQLLogoFile,
            ],
        ];

        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $items[] = array_merge(
                $commonPluginData,
                [
                    'name' => $moduleResolver->getName($module),
                    'slug' => $moduleResolver->getSlug($module),
                    'short_description' => $moduleResolver->getDescription($module),
                    'homepage' => $moduleResolver->getWebsiteURL($module),
                    'gato_extension_module' => $module,
                    'gato_extension_slug' => $moduleResolver->getGatoGraphQLExtensionSlug($module),
                ]
            );
        }
        return $items;
    }
}
