<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\Module;

/**
 * To be used by those packages which provide meta directives,
 * as `@start` and `@end` are helper directives to indicate
 * which directives are affected by a meta directive.
 */
trait EnableStartEndHelperDirectivesModuleTrait
{
    /**
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
        parent::customizeModuleClassConfiguration($moduleClassConfiguration);
        /**
         * Enable it by default only, so that it can still be
         * explicitly disabled via configuration.
         */
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_START_END_HELPER_DIRECTIVES] ??= true;
    }
}
