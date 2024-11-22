<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Container\ContainerManagerInterface;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

use function did_action;
use function flush_rewrite_rules;

class ContainerManager implements ContainerManagerInterface
{
    private ?UserSettingsManagerInterface $userSettingsManager = null;

    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    public function flushContainer(
        bool $flushRewriteRules,
        ?bool $regenerateContainer,
    ): void {
        if ($flushRewriteRules) {
            /**
             * Watch out: `flush_rewrite_rules` must be called at the end,
             * after the new CPTs are registered (eg: when an extension is
             * activated). Otherwise, loading the single post for those CPTs
             * doesn't work.
             */
            if (did_action('init') > 0) {
                flush_rewrite_rules();
            } else {
                add_action('init', flush_rewrite_rules(...), PHP_INT_MAX);
            }
        }

        /**
         * Update the timestamp, and maybe regenerate
         * the service container.
         */
        if ($regenerateContainer === null) {
            /**
             * The System/Application Service Containers need to be regenerated
             * when updating the plugin Settings only if Services can be added
             * or not to the Container based on the context.
             *
             * @var ComponentModelModuleConfiguration
             */
            $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
            $regenerateContainer = $moduleConfiguration->supportDefiningServicesInTheContainerBasedOnTheContext();
        }
        if ($regenerateContainer) {
            $this->getUserSettingsManager()->storeContainerTimestamp();
        } else {
            $this->getUserSettingsManager()->storeOperationalTimestamp();
        }
    }
}
