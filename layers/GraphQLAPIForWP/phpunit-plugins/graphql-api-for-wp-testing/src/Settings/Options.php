<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Settings;

/**
 * Option names
 */
class Options
{
    /**
     * Option name to store a flag for the REST API, to indicate to
     * execute `flush_rewrite_rules` in the upcoming request.
     */
    public final const FLUSH_REWRITE_RULES = 'graphql-api-flush-rewrite-rules';
}
