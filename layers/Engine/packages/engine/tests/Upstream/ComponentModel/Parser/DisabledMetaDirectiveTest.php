<?php

declare(strict_types=1);

namespace PoP\Engine\Upstream\ComponentModel\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Location;

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
