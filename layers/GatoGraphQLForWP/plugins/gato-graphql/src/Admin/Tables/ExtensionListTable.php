<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;

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
        $commonPluginData = $this->getCommonPluginData();

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
                    'slug' => $moduleResolver->getGatoGraphQLExtensionSlug($module),
                    'short_description' => $moduleResolver->getDescription($module),
                    'homepage' => $moduleResolver->getWebsiteURL($module),

                    /**
                     * These are custom properties, not required by the upstream class,
                     * but used internally to modify the generated HTML content
                     */
                    'gato_extension_module' => $module,
                ]
            );
        }
        return $items;
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
        return \admin_url(sprintf(
            'admin.php?page=%s&%s=%s&%s=%s&TB_iframe=true&width=600&height=550',
            App::request('page') ?? App::query('page', ''),
            RequestParams::TAB,
            RequestParams::TAB_DOCS,
            RequestParams::MODULE,
            urlencode($extensionModule)
        ));
    }
}
