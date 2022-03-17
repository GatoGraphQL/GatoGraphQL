<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Constants;

class ClientSymbols
{
    /**
     * Hack to add support for query batching from GraphiQL:
     * When in GraphiQL running query ```query __ALL { id }```,
     * it will execute all the other queries in the document
     */
    const GRAPHIQL_QUERY_BATCHING_OPERATION_NAME = '__ALL';
}
