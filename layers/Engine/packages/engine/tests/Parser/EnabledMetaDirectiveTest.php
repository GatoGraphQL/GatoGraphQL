<?php

declare(strict_types=1);

namespace PoP\Engine\Parser;

use PoP\ComponentModel\GraphQLParser\Parser\ExtendedParser;
use PoP\GraphQLParser\Parser\Ast\Directive;
use PoP\GraphQLParser\Parser\Ast\Document;
use PoP\GraphQLParser\Parser\Ast\LeafField;
use PoP\GraphQLParser\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Parser\ExtendedParserInterface;
use PoP\Root\AbstractTestCase;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\InlineFragment;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\ParserTest;

class EnabledMetaDirectiveTest extends AbstractMetaDirectiveTest
{
    protected static function enableComposableDirectives(): bool
    {
        return true;
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
                                        new MetaDirective(
                                            'forEach',
                                            [
                                                // new Argument('if', new Literal(true, new Location(2, 28)), new Location(2, 24)),
                                            ],
                                            [
                                                new Directive('upperCase', [], new Location(2, 32)),
                                            ],
                                            new Location(2, 23)
                                        )
                                    ],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
            ],
            [
                <<<GRAPHQL
                    query {
                        capabilities @forEach(affectDirectivesUnderPos: [1]) @upperCase
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
                                        new MetaDirective(
                                            'forEach',
                                            [
                                                new Argument('affectDirectivesUnderPos', new InputList([1], new Location(2, 57)), new Location(2, 31)),
                                            ],
                                            [
                                                new Directive('upperCase', [], new Location(2, 63)),
                                            ],
                                            new Location(2, 23)
                                        )
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
