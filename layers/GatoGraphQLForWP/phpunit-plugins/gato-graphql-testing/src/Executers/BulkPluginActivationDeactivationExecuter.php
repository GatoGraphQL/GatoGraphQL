<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
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
            $this->executeBulkPluginDeactivation();
            $message = \__('Plugins deactivated successfully');
        } else {
            $this->executeBulkPluginActivation();
            $message = \__('Plugins activated successfully');
        }
        
        // There's no need to keep execution, objective achieved!
        $this->outputJSONResponseAndExit(['message' => $message]);
    }

    protected function executeBulkPluginDeactivation(): void
    {
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

    protected function executeBulkPluginActivation(): void
    {
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
}
