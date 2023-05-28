<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\Module;

use PoP\Root\Module\AbstractModule;

abstract class AbstractEnableDynamicVariablesModule extends AbstractModule
{
    /**
     * These environment variables must be `true` for "passOnwardsAs" to work
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_DYNAMIC_VARIABLES] = true;
    }
}
