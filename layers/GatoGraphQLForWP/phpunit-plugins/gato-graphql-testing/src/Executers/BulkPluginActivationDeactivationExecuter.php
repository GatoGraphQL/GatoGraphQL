<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginLifecyclePriorities;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\Root\Constants\HookNames;
use RuntimeException;

/**
 * When the corresponding params are passed in the URL,
 * either activate all Gato GraphQL extensions, or deactivate
 * them all except the indicated ones.
 *
 * This Executer helps test that a single activate extension
 * works as expected (eg: it has no dependencies on any other
 * extension, only on Gato GraphQL).
 */
class BulkPluginActivationDeactivationExecuter
{
    use GraphQLServerTestExecuterTrait;

    public function __construct()
    {
        \add_action(
            PluginAppHooks::INITIALIZE_APP,
            $this->addHooks(...),
            PluginLifecyclePriorities::INITIALIZE_APP
        );
    }

    public function addHooks(string $pluginAppGraphQLServerName): void
    {
        if ($pluginAppGraphQLServerName !== PluginAppGraphQLServerNames::EXTERNAL) {
            return;
        }

        App::addAction(
            HookNames::APPLICATION_READY,
            $this->maybeExecute(...)
        );
    }

    protected function maybeExecute(): void
    {
        if (!App::hasState('actions')) {
            return;
        }

        /** @var string[] */
        $actions = App::getState('actions');

        /** @var string[] $actions */
        $executeBulkPluginActivation = in_array(Actions::EXECUTE_BULK_PLUGIN_ACTIVATION, $actions);
        $executeBulkPluginDeactivation = in_array(Actions::EXECUTE_BULK_PLUGIN_DEACTIVATION, $actions);
        if (!$executeBulkPluginActivation && !$executeBulkPluginDeactivation) {
            return;
        }

        if ($executeBulkPluginDeactivation) {
            if (!App::getRequest()->query->has(Params::SKIP_DEACTIVATING_PLUGIN_FILES)) {
                throw new RuntimeException(
                    sprintf(
                        \__('Must provide parameter "%s" when bulk deactivating plugins'),
                        Params::SKIP_DEACTIVATING_PLUGIN_FILES
                    ),
                );
            }
            /** @var string[] */
            $skipDeactivatingPlugins = App::getRequest()->query->all()[Params::SKIP_DEACTIVATING_PLUGIN_FILES];
        }

        // Obtain the list of all the Gato GraphQL Extensions
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        /** @var string[] */
        $gatoGraphQLExtensionPluginFiles = [];
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            // Do not activate the Bundles, only the Extensions
            if ($moduleResolver instanceof BundleExtensionModuleResolverInterface) {
                continue;
            }
            $gatoGraphQLExtensionPluginFiles[] = $moduleResolver->getGatoGraphQLExtensionPluginFile($module);
        }

        // Load the WordPress file with the functions
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        if ($executeBulkPluginDeactivation) {
            $gatoGraphQLExtensionsToDeactivate = array_diff(
                $gatoGraphQLExtensionPluginFiles,
                $skipDeactivatingPlugins
            );
            \deactivate_plugins($gatoGraphQLExtensionsToDeactivate);
            $message = sprintf(
                \__('Deactivated plugins: "%s"'),
                implode('", "', $gatoGraphQLExtensionsToDeactivate)
            );
        } else {
            \activate_plugins($gatoGraphQLExtensionPluginFiles);
            $message = sprintf(
                \__('Activated plugins: "%s"'),
                implode('", "', $gatoGraphQLExtensionPluginFiles)
            );
        }

        // There's no need to keep execution, objective achieved!
        $this->outputJSONResponseAndExit(['message' => $message]);
    }
}
