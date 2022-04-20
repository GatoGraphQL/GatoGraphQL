<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit;

use PoP\Engine\ObjectModels\Root;

class QueryExecutionGraphQLServerTest extends AbstractQueryExecutionGraphQLServerTestCase
{
    public function graphQLServerExecutionProvider(): array
    {
        return [
            'id' => [
                '
                {
                    id
                }
                ',
                [
                    'data' => [
                        'id' => Root::ID,
                    ]
                ]
            ],
        ];
    }
}
