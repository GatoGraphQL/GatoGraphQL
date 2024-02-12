<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests\Constants;

/**
 * Watch out: the constants in this class are referenced from
 * the Gato GraphQL Testing plugin, however this class is not
 * included there, hence its values are hardcoded. Update
 * with caution!
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/src/Plugin.php
 *
 * @todo Create a new package to make it DRY
 */
class CustomHeaders
{
    public const REQUEST_ORIGIN = 'X-Request-Origin';

    /**
     * Duplicated constant
     *
     * @see layers/GatoGraphQLForWP/plugins/testing-schema/src/Constants/CustomHeaders.php
     */
    public const GATOGRAPHQL_ERRORS = 'X-GATOGRAPHQL-ERRORS';
}
