<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Controllers;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Settings\Options;

use function update_option;

trait WithFlushRewriteRulesRESTControllerTrait
{
    /**
     * Must flush the rewrite rules, so that the disabled client
     * shows a 404.
     *
     * But calling `flush_rewrite_rules` directly
     * here doesn't work!
     *
     * So instead, save a flag in the options, and check if
     * to do the flush at the beginning of the next request.
     */
    protected function enqueueFlushRewriteRules(): void
    {
        update_option(Options::FLUSH_REWRITE_RULES, true);
    }
}
