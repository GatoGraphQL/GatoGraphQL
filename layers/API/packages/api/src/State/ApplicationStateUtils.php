<?php

declare(strict_types=1);

namespace PoP\API\State;

use PoP\API\Facades\FieldQueryConvertorFacade;

class ApplicationStateUtils
{
    /**
     * The query as an array goes straight into $vars['query'].
     *
     * The query as string must be converted to array, which has 2 outputs:
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
    public static function maybeConvertQueryAndAddToVars(array &$vars, array|string $query): void
    {
        // The fields param can either be an array or a string. Convert them to array
        if (is_array($query)) {
            $vars['query'] = $query;
        } elseif (is_string($query)) {
            $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
            $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($query);
            $vars['query'] = $fieldQuerySet->getExecutableFieldQuery();
            if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
                $vars['requested-query'] = $fieldQuerySet->getRequestedFieldQuery();
            }
        }
    }
}
