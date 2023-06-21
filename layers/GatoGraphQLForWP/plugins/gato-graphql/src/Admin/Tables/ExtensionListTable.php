<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;

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
            $items[] = [
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
                'gato_extension_is_bundle' => $moduleResolver instanceof BundleExtensionModuleResolverInterface,
            ];
        }
        return $this->combineExtensionItemsWithCommonPluginData($items);
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
}
