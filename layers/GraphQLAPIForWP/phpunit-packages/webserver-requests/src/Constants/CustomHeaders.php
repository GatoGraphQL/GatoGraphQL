<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests\Constants;

/**
 * Watch out: the constants in this class are referenced from
 * the GraphQL API Testing plugin, however this class is not
 * included there, hence its values are hardcoded. Update
 * with caution!
 *
 * @see layers/GraphQLAPIForWP/phpunit-plugins/graphql-api-for-wp-testing/src/Plugin.php
 */
class CustomHeaders
{
    public const REQUEST_ORIGIN = 'X-Request-Origin';
}
