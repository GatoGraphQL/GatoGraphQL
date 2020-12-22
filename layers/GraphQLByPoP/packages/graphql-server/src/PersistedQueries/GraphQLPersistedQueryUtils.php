<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\PersistedQueries;

use PoP\API\Facades\PersistedQueryManagerFacade;
use PoP\API\Facades\PersistedFragmentManagerFacade;
use GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade;

/**
 * Watch out! The GraphqL queries will be parsed ALWAYS, for every request!
 * So it's not a good idea to add them... Should add
 * some other layer that parses the query only if it is requested (TODO!)
 */
class GraphQLPersistedQueryUtils
{
    public static function addPersistedQuery(string $queryName, string $graphQLQuery, ?string $description = null): void
    {
        $queryCatalogueManager = PersistedQueryManagerFacade::getInstance();
        $graphQLQueryConvertor = GraphQLQueryConvertorFacade::getInstance();
        list(
            $operationType,
            $fieldQuery
        ) = $graphQLQueryConvertor->convertFromGraphQLToFieldQuery($graphQLQuery);
        $queryCatalogueManager->add($queryName, $fieldQuery, $description);
    }

    public static function addPersistedFragment(string $fragmentName, string $graphQLFragment, ?string $description = null): void
    {
        $fragmentCatalogueManager = PersistedFragmentManagerFacade::getInstance();
        $graphQLQueryConvertor = GraphQLQueryConvertorFacade::getInstance();
        list(
            $operationType,
            $fieldQuery
        ) = $graphQLQueryConvertor->convertFromGraphQLToFieldQuery($graphQLFragment);
        $fragmentCatalogueManager->add($fragmentName, $fieldQuery, $description);
    }
}
