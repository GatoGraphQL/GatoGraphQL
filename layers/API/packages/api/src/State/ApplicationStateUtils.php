<?php

declare(strict_types=1);

namespace PoP\API\State;

use PoP\API\Facades\FieldQueryConvertorFacade;

class ApplicationStateUtils
{
    /**
     * The GraphQL query must be parsed into the AST, which has 2 outputs:
     *
     * 1. The actual requested query
     * 2. The executable query, created by doing transformations on the requested query
     *
     * For instance, when doing query batching, fields in the executable query
     * will be prepended with "self" to have the operations be executed in strict order.
     *
     * The executable query is the one needed to load data, so it's saved under "query".
     * The requested query is used to display the data, for instance for GraphQL.
     * It's saved under "requested-query" in $vars, and it's optional: if empty,
     * requested = executable => the executable query from $vars['query'] can be used
     */
    public static function parseGraphQLQueryAndAddToVars(array &$vars, string $query): void
    {
        $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
        $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($query);
        $vars['query'] = $fieldQuerySet->getExecutableFieldQuery();
        if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
            $vars['requested-query'] = $fieldQuerySet->getRequestedFieldQuery();
        }
    }
}
