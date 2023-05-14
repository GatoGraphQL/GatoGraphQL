<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
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

        /**
         * Add an additional and artificial "Request an extension" item
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $items[] = array_merge(
            $commonPluginData,
            [
                'name' => \__('Request an Extension', 'gato-graphql'),
                'slug' => 'artificial-request-an-extension',
                'short_description' => \__('Needing some extra functionality, or an integration with some plugin? Let\'s make it happen.', 'gato-graphql'),
                'homepage' => $moduleConfiguration->getGatoGraphQLRequestExtensionPageURL(),
            ]
        );

        return $items;
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdaptedDetailsLink(array $plugin): ?string
    {
        /**
         * This is a custom property, not required by the upstream class,
         * but used internally to modify the generated HTML content
         *
         * @var string|null
         */
        $extensionModule = $plugin['gato_extension_module'] ?? null;
        if ($extensionModule === null) {
            return null;
        }
        return $this->getOpeningModuleDocInModalLinkURL(
            App::request('page') ?? App::query('page', ''),
            $extensionModule,
        );
    }
}
