<?php

declare(strict_types=1);

namespace PoP\Engine\Parser;

use PoP\GraphQLParser\Parser\Ast\Directive;
use PoP\GraphQLParser\Parser\Ast\Document;
use PoP\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Location;

class DisabledMetaDirectiveTest extends AbstractMetaDirectiveTest
{
    protected static function enableComposableDirectives(): bool
    {
        return false;
    }

    public function queryWithMetaDirectiveProvider()
    {
        return [
            [
                <<<GRAPHQL
                    query {
                        capabilities @forEach @upperCase
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            '',
                            [],
                            [],
                            [
                                new LeafField(
                                    'capabilities',
                                    null,
                                    [],
                                    [
                                        new Directive('forEach', [], new Location(2, 23)),
                                        new Directive('upperCase', [], new Location(2, 32)),
                                    ],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
            ],
        ];
    }
}
