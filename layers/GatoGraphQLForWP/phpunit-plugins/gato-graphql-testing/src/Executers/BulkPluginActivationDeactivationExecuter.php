<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginLifecyclePriorities;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
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
            $this->maybeExecute(...),
            PluginLifecyclePriorities::INITIALIZE_APP
        );
    }

    protected function maybeExecute(): void
    {
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $actions = $_GET['actions'] ?? null;
        if ($actions === null || !is_array($actions)) {
            return;
        }

        /** @var string[] $actions */
        $executeBulkPluginActivation = in_array(Actions::EXECUTE_BULK_PLUGIN_ACTIVATION, $actions);
        $executeBulkPluginDeactivation = in_array(Actions::EXECUTE_BULK_PLUGIN_DEACTIVATION, $actions);
        if (!$executeBulkPluginActivation && $executeBulkPluginDeactivation) {
            return;
        }

        if ($executeBulkPluginDeactivation) {
            $skipDeactivatingPlugins = $_GET[Params::SKIP_DEACTIVATING_PLUGINS];
            if ($skipDeactivatingPlugins === null || !is_array($skipDeactivatingPlugins)) {
                throw new RuntimeException(
                    sprintf(
                        \__('Must provide parameter "%s" when bulk deactivating plugins'),
                        Params::SKIP_DEACTIVATING_PLUGINS
                    ),
                );
            }
        }

        // Obtain the list of all the Gato GraphQL Extensions
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        /** @var string[] */
        $gatoGraphQLExtensions = [];
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $gatoGraphQLExtensions = [
                ...$gatoGraphQLExtensions,
                $moduleResolver->getModulesToResolve()
            ];
        }

        if ($executeBulkPluginDeactivation) {
            $this->executeBulkPluginDeactivation($gatoGraphQLExtensions);
            $message = \__('Plugins deactivated successfully');
        } else {
            $this->executeBulkPluginActivation($gatoGraphQLExtensions);
            $message = \__('Plugins activated successfully');
        }
        
        // There's no need to keep execution, objective achieved!
        $this->outputJSONResponseAndExit(['message' => $message]);
    }
}
