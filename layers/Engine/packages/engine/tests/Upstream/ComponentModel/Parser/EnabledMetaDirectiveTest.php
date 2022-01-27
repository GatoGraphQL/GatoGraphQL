<?php

declare(strict_types=1);

namespace PoP\Engine\Upstream\ComponentModel\Parser;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\Directive;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Location;

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
                                            [],
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
            [
                <<<GRAPHQL
                    query {
                        capabilities @forEach(affectDirectivesUnderPos: [1,2]) @upperCase @lowerCase
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
                                                new Argument('affectDirectivesUnderPos', new InputList([1, 2], new Location(2, 57)), new Location(2, 31)),
                                            ],
                                            [
                                                new Directive('upperCase', [], new Location(2, 65)),
                                                new Directive('lowerCase', [], new Location(2, 76)),
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
                        groupCapabilities @forEach @advancePointerInArrayOrObject(path: "group") @upperCase
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
                                    'groupCapabilities',
                                    null,
                                    [],
                                    [
                                        new MetaDirective(
                                            'forEach',
                                            [],
                                            [
                                                new MetaDirective(
                                                    'advancePointerInArrayOrObject',
                                                    [
                                                        new Argument('path', new Literal('group', new Location(2, 74)), new Location(2, 67)),
                                                    ],
                                                    [
                                                        new Directive('upperCase', [], new Location(2, 83)),
                                                    ],
                                                    new Location(2, 37)
                                                )
                                            ],
                                            new Location(2, 28)
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
                        groupCapabilities @forEach @advancePointerInArrayOrObject(path: "group") @upperCase @lowerCase
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
                                    'groupCapabilities',
                                    null,
                                    [],
                                    [
                                        new MetaDirective(
                                            'forEach',
                                            [],
                                            [
                                                new MetaDirective(
                                                    'advancePointerInArrayOrObject',
                                                    [
                                                        new Argument('path', new Literal('group', new Location(2, 74)), new Location(2, 67)),
                                                    ],
                                                    [
                                                        new Directive('upperCase', [], new Location(2, 83)),
                                                    ],
                                                    new Location(2, 37)
                                                )
                                            ],
                                            new Location(2, 28)
                                        ),
                                        new Directive('lowerCase', [], new Location(2, 94)),
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
                        groupCapabilities @forEach @upperCase @advancePointerInArrayOrObject(path: "group") @lowerCase
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
                                    'groupCapabilities',
                                    null,
                                    [],
                                    [
                                        new MetaDirective(
                                            'forEach',
                                            [],
                                            [
                                                new Directive('upperCase', [], new Location(2, 37)),
                                            ],
                                            new Location(2, 28)
                                        ),
                                        new MetaDirective(
                                            'advancePointerInArrayOrObject',
                                            [
                                                new Argument('path', new Literal('group', new Location(2, 85)), new Location(2, 78)),
                                            ],
                                            [
                                                new Directive('lowerCase', [], new Location(2, 94)),
                                            ],
                                            new Location(2, 48)
                                        ),
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
                        groupCapabilities @forEach @advancePointerInArrayOrObject(path: "group", affectDirectivesUnderPos: [1,2]) @upperCase @lowerCase
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
                                    'groupCapabilities',
                                    null,
                                    [],
                                    [
                                        new MetaDirective(
                                            'forEach',
                                            [],
                                            [
                                                new MetaDirective(
                                                    'advancePointerInArrayOrObject',
                                                    [
                                                        new Argument('path', new Literal('group', new Location(2, 74)), new Location(2, 67)),
                                                        new Argument('affectDirectivesUnderPos', new InputList([1, 2], new Location(2, 108)), new Location(2, 82)),
                                                    ],
                                                    [
                                                        new Directive('upperCase', [], new Location(2, 116)),
                                                        new Directive('lowerCase', [], new Location(2, 127)),
                                                    ],
                                                    new Location(2, 37)
                                                )
                                            ],
                                            new Location(2, 28)
                                        ),
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
                        groupCapabilities @forEach(affectDirectivesUnderPos: [1,3]) @advancePointerInArrayOrObject(path: "group") @upperCase @lowerCase
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
                                    'groupCapabilities',
                                    null,
                                    [],
                                    [
                                        new MetaDirective(
                                            'forEach',
                                            [
                                                new Argument('affectDirectivesUnderPos', new InputList([1, 3], new Location(2, 62)), new Location(2, 36)),
                                            ],
                                            [
                                                new MetaDirective(
                                                    'advancePointerInArrayOrObject',
                                                    [
                                                        new Argument('path', new Literal('group', new Location(2, 107)), new Location(2, 100)),
                                                    ],
                                                    [
                                                        new Directive('upperCase', [], new Location(2, 116)),
                                                    ],
                                                    new Location(2, 70)
                                                ),
                                                new Directive('lowerCase', [], new Location(2, 127)),
                                            ],
                                            new Location(2, 28)
                                        ),
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

    /**
     * @dataProvider failingQueryWithMetaDirectiveProvider
     */
    public function testFailingMetaDirectives(string $query)
    {
        $parser = $this->getParser();

        $this->expectException(InvalidRequestException::class);
        $parser->parse($query);
    }

    public function failingQueryWithMetaDirectiveProvider(): array
    {
        return [
            'no-directive-under-pos' => [
                <<<GRAPHQL
                    query {
                        capabilities @forEach(affectDirectivesUnderPos: [2]) @upperCase
                    }
                GRAPHQL,
            ],
            'no-negative-pos' => [
                <<<GRAPHQL
                    query {
                        capabilities @forEach(affectDirectivesUnderPos: [-2]) @upperCase
                    }
                GRAPHQL,
            ],
            'no-2nd-directive-under-pos' => [
                <<<GRAPHQL
                    query {
                        capabilities @forEach(affectDirectivesUnderPos: [1,2]) @upperCase
                    }
                GRAPHQL,
            ],
            'directive-referenced-only-once' => [
                <<<GRAPHQL
                    query {
                        groupCapabilities @forEach(affectDirectivesUnderPos: [1,2]) @advancePointerInArrayOrObject(path: "group") @upperCase @lowerCase
                    }
                GRAPHQL
            ],
        ];
    }
}
