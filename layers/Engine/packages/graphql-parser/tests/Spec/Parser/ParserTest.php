<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\Exception\Parser\UnsupportedSyntaxErrorParserException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLParserErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLUnsupportedFeatureErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\SubscriptionOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
use stdClass;

class ParserTest extends AbstractTestCase
{
    private ?ParserInterface $parser = null;

    protected function getParser(): ParserInterface
    {
        return $this->parser ??= new Parser();
    }

    public function testEmptyParser(): void
    {
        $parser = $this->getParser();
        $document = $parser->parse('');

        $this->assertEquals(new Document([], []), $document);
    }

    public function testInvalidSelection(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, [Token::tokenName(Token::TYPE_RBRACE)]))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
        {
            test {
                id
                image {
                    
                }
            }
        }
        ');
    }

    public function testComments(): void
    {
        $query = <<<GRAPHQL
# asdasd "asdasdasd"
# comment line 2

query {
    authors (category: "#2") { #asda asd
        _id
    }
}
GRAPHQL;

        $parser     = $this->getParser();
        $document = $parser->parse($query);

        $this->assertEquals($document, new Document(
            [
                new QueryOperation(
                    '',
                    [],
                    [],
                    [
                        new RelationalField(
                            'authors',
                            null,
                            [
                                new Argument('category', new Literal('#2', new Location(5, 25)), new Location(5, 14)),
                            ],
                            [
                                new LeafField('_id', null, [], [], new Location(6, 9)),
                            ],
                            [],
                            new Location(5, 5)
                        ),
                    ],
                    new Location(4, 7)
                )
            ]
        ));

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query { authors(category: "#2") { _id } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    private function tokenizeStringContents(string $graphQLString): Token
    {
        $parser = new TokenizerTestingParser();
        $parser->initTokenizerForTesting('"' . $graphQLString . '"');

        return $parser->getTokenForTesting();
    }


    public function testEscapedStrings(): void
    {
        $this->assertEquals(
            [
                $this->tokenizeStringContents(""),
                $this->tokenizeStringContents("x"),
                $this->tokenizeStringContents("\\\""),
                $this->tokenizeStringContents("\\/"),
                $this->tokenizeStringContents("\\f"),
                $this->tokenizeStringContents("\\n"),
                $this->tokenizeStringContents("\\r"),
                $this->tokenizeStringContents("\\b"),
                $this->tokenizeStringContents("\\uABCD")
            ],
            [
                new Token(Token::TYPE_STRING, 1, 1, ""),
                new Token(Token::TYPE_STRING, 1, 2, "x"),
                new Token(Token::TYPE_STRING, 1, 3, '"'),
                new Token(Token::TYPE_STRING, 1, 3, '/'),
                new Token(Token::TYPE_STRING, 1, 3, "\f"),
                new Token(Token::TYPE_STRING, 1, 3, "\n"),
                new Token(Token::TYPE_STRING, 1, 3, "\r"),
                new Token(Token::TYPE_STRING, 1, 3, sprintf("%c", 8)),
                new Token(Token::TYPE_STRING, 1, 7, html_entity_decode("&#xABCD;", ENT_QUOTES, 'UTF-8'))
            ]
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('wrongQueriesProvider')]
    public function testWrongQueries(string $query): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $parser = $this->getParser();

        $parser->parse($query);
    }

    /**
     * @return mixed[]
     */
    public static function wrongQueriesProvider(): array
    {
        return [
            ['{ test (a: "asd", b: <basd>) { id }'],
            ['{ test (asd: [..., asd]) { id } }'],
            ['{ test (asd: { "a": 4, "m": null, "asd": false  "b": 5, "c" : { a }}) { id } }'],
            ['{ test (a: """asd") { id }'],
            ['{ test (a: "asd""") { id }'],
            ['asdasd'],
            ['mutation { test(asd: ... ){ ...,asd, asd } }'],
            ['mutation { test{ . test on Test { id } } }'],
            ['mutation { test( a: "asdd'],
            ['mutation { test( a: { "asd": 12 12'],
            ['mutation { test( a: { "asd": 12'],
        ];
    }

    public function testCommas(): void
    {
        $parser = $this->getParser();
        $document   = $parser->parse('{ foo,       ,,  , bar  }');
        $this->assertEquals(new Document(
            [
                new QueryOperation(
                    '',
                    [],
                    [],
                    [
                        new LeafField('foo', '', [], [], new Location(1, 3)),
                        new LeafField('bar', '', [], [], new Location(1, 20)),
                    ],
                    new Location(1, 1)
                )
            ]
        ), $document);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query { foo bar }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testQueryWithNoFields(): void
    {
        $parser = $this->getParser();
        $document   = $parser->parse('{ name }');
        $this->assertEquals(new Document(
            [
                new QueryOperation(
                    '',
                    [],
                    [],
                    [
                        new LeafField('name', '', [], [], new Location(1, 3)),
                    ],
                    new Location(1, 1)
                )
            ]
        ), $document);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query { name }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testQueryWithFields(): void
    {
        $parser = $this->getParser();
        $document   = $parser->parse('{ post, user { name } }');
        $this->assertEquals(new Document(
            [
                new QueryOperation(
                    '',
                    [],
                    [],
                    [
                        new LeafField('post', null, [], [], new Location(1, 3)),
                        new RelationalField('user', null, [], [
                            new LeafField('name', null, [], [], new Location(1, 16)),
                        ], [], new Location(1, 9)),
                    ],
                    new Location(1, 1)
                )
            ]
        ), $document);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query { post user { name } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testFragmentWithFields(): void
    {
        $parser = $this->getParser();
        $document   = $parser->parse('
            fragment FullType on __Type {
                kind
                fields {
                    name
                }
            }');
        $this->assertEquals(new Document(
            [],
            [
                new Fragment('FullType', '__Type', [], [
                    new LeafField('kind', null, [], [], new Location(3, 17)),
                    new RelationalField('fields', null, [], [
                        new LeafField('name', null, [], [], new Location(5, 21)),
                    ], [], new Location(4, 17)),
                ], new Location(2, 22)),
            ]
        ), $document);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'fragment FullType on __Type { kind fields { name } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testInspectionQuery(): void
    {
        $parser = $this->getParser();

        $document = $parser->parse('
            query IntrospectionQuery {
                __schema {
                    queryType { name }
                    mutationType { name }
                    types {
                        ...FullType
                    }
                    directives {
                        name
                        description
                        args {
                            ...InputValue
                        }
                        onOperation
                        onFragment
                        onField
                    }
                }
            }

            fragment FullType on __Type {
                kind
                name
                description
                fields {
                    name
                    description
                    args {
                        ...InputValue
                    }
                    type {
                        ...TypeRef
                    }
                    isDeprecated
                    deprecationReason
                }
                inputFields {
                    ...InputValue
                }
                interfaces {
                    ...TypeRef
                }
                enumValues {
                    name
                    description
                    isDeprecated
                    deprecationReason
                }
                possibleTypes {
                    ...TypeRef
                }
            }

            fragment InputValue on __InputValue {
                name
                description
                type { ...TypeRef }
                defaultValue
            }

            fragment TypeRef on __Type {
                kind
                name
                ofType {
                    kind
                    name
                    ofType {
                        kind
                        name
                        ofType {
                            kind
                            name
                        }
                    }
                }
            }
        ');

        $this->assertEquals(new Document(
            [
                new QueryOperation(
                    'IntrospectionQuery',
                    [],
                    [],
                    [
                        new RelationalField('__schema', null, [], [
                            new RelationalField('queryType', null, [], [
                                new LeafField('name', null, [], [], new Location(4, 33)),
                            ], [], new Location(4, 21)),
                            new RelationalField('mutationType', null, [], [
                                new LeafField('name', null, [], [], new Location(5, 36)),
                            ], [], new Location(5, 21)),
                            new RelationalField('types', null, [], [
                                new FragmentReference('FullType', new Location(7, 28)),
                            ], [], new Location(6, 21)),
                            new RelationalField('directives', null, [], [
                                new LeafField('name', null, [], [], new Location(10, 25)),
                                new LeafField('description', null, [], [], new Location(11, 25)),
                                new RelationalField('args', null, [], [
                                    new FragmentReference('InputValue', new Location(13, 32)),
                                ], [], new Location(12, 25)),
                                new LeafField('onOperation', null, [], [], new Location(15, 25)),
                                new LeafField('onFragment', null, [], [], new Location(16, 25)),
                                new LeafField('onField', null, [], [], new Location(17, 25)),
                            ], [], new Location(9, 21)),
                        ], [], new Location(3, 17)),
                    ],
                    new Location(2, 19)
                )
            ],
            [
                new Fragment('FullType', '__Type', [], [
                    new LeafField('kind', null, [], [], new Location(23, 17)),
                    new LeafField('name', null, [], [], new Location(24, 17)),
                    new LeafField('description', null, [], [], new Location(25, 17)),
                    new RelationalField('fields', null, [], [
                        new LeafField('name', null, [], [], new Location(27, 21)),
                        new LeafField('description', null, [], [], new Location(28, 21)),
                        new RelationalField('args', null, [], [
                            new FragmentReference('InputValue', new Location(30, 28)),
                        ], [], new Location(29, 21)),
                        new RelationalField('type', null, [], [
                            new FragmentReference('TypeRef', new Location(33, 28)),
                        ], [], new Location(32, 21)),
                        new LeafField('isDeprecated', null, [], [], new Location(35, 21)),
                        new LeafField('deprecationReason', null, [], [], new Location(36, 21)),
                    ], [], new Location(26, 17)),
                    new RelationalField('inputFields', null, [], [
                        new FragmentReference('InputValue', new Location(39, 24)),
                    ], [], new Location(38, 17)),
                    new RelationalField('interfaces', null, [], [
                        new FragmentReference('TypeRef', new Location(42, 24)),
                    ], [], new Location(41, 17)),
                    new RelationalField('enumValues', null, [], [
                        new LeafField('name', null, [], [], new Location(45, 21)),
                        new LeafField('description', null, [], [], new Location(46, 21)),

                        new LeafField('isDeprecated', null, [], [], new Location(47, 21)),
                        new LeafField('deprecationReason', null, [], [], new Location(48, 21)),
                    ], [], new Location(44, 17)),
                    new RelationalField('possibleTypes', null, [], [
                        new FragmentReference('TypeRef', new Location(51, 24)),
                    ], [], new Location(50, 17)),
                ], new Location(22, 22)),
                new Fragment('InputValue', '__InputValue', [], [
                    new LeafField('name', null, [], [], new Location(56, 17)),
                    new LeafField('description', null, [], [], new Location(57, 17)),
                    new RelationalField('type', null, [], [
                        new FragmentReference('TypeRef', new Location(58, 27)),
                    ], [], new Location(58, 17)),
                    new LeafField('defaultValue', null, [], [], new Location(59, 17)),
                ], new Location(55, 22)),
                new Fragment('TypeRef', '__Type', [], [
                    new LeafField('kind', null, [], [], new Location(63, 17)),
                    new LeafField('name', null, [], [], new Location(64, 17)),
                    new RelationalField('ofType', null, [], [
                        new LeafField('kind', null, [], [], new Location(66, 21)),
                        new LeafField('name', null, [], [], new Location(67, 21)),
                        new RelationalField('ofType', null, [], [
                            new LeafField('kind', null, [], [], new Location(69, 25)),
                            new LeafField('name', null, [], [], new Location(70, 25)),
                            new RelationalField('ofType', null, [], [
                                new LeafField('kind', null, [], [], new Location(72, 29)),
                                new LeafField('name', null, [], [], new Location(73, 29)),
                            ], [], new Location(71, 25)),
                        ], [], new Location(68, 21)),
                    ], [], new Location(65, 17)),
                ], new Location(62, 22)),
            ]
        ), $document);
    }

    public function testInlineFragment(): void
    {
        $parser = $this->getParser();
        $document = $parser->parse('
            {
                test: test {
                    name,
                    ... on UnionType {
                        unionName
                    }
                }
            }
        ');

        $this->assertEquals($document, new Document(
            [
                new QueryOperation(
                    '',
                    [],
                    [],
                    [
                        new RelationalField(
                            'test',
                            'test',
                            [],
                            [
                                new LeafField('name', null, [], [], new Location(4, 21)),
                                new InlineFragment('UnionType', [new LeafField('unionName', null, [], [], new Location(6, 25))], [], new Location(5, 28)),
                            ],
                            [],
                            new Location(3, 23)
                        ),
                    ],
                    new Location(2, 13)
                )
            ]
        ));

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query { test: test { name ...on UnionType { unionName } } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('mutationProvider')]
    public function testMutations(
        string $query,
        Document $document,
        string $documentAsStr
    ): void {
        $parser = $this->getParser();

        // 1st test: Parsing is right
        $this->assertEquals(
            $document,
            $parser->parse($query)
        );

        // 2nd test: Converting document back to query string is right
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    /**
     * @return mixed[]
     */
    public static function mutationProvider(): array
    {
        $variable = new Variable('variable', 'Int', false, false, false, false, false, [], new Location(1, 8));
        return [
            [
                'query ($variable: Int){ query ( teas: $variable ) { alias: name } }',
                new Document(
                    [
                        new QueryOperation(
                            '',
                            [
                                $variable,
                            ],
                            [],
                            [
                                new RelationalField(
                                    'query',
                                    null,
                                    [
                                        new Argument('teas', new VariableReference('variable', $variable, new Location(1, 39)), new Location(1, 33)),
                                    ],
                                    [
                                        new LeafField('name', 'alias', [], [], new Location(1, 60)),
                                    ],
                                    [],
                                    new Location(1, 25)
                                ),
                            ],
                            new Location(1, 7)
                        )
                    ]
                ),
                'query ($variable: Int) { query(teas: $variable) { alias: name } }',
            ],
            [
                '{ query { alias: name } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('query', null, [], [new LeafField('name', 'alias', [], [], new Location(1, 18))], [], new Location(1, 3)),
                        ], new Location(1, 1)),
                    ]
                ),
                'query { query { alias: name } }',
            ],
            [
                'mutation { createUser ( email: "test@test.com", active: true ) { id } }',
                new Document(
                    [
                        new MutationOperation(
                            '',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'createUser',
                                    null,
                                    [
                                        new Argument('email', new Literal('test@test.com', new Location(1, 33)), new Location(1, 25)),
                                        new Argument('active', new Literal(true, new Location(1, 57)), new Location(1, 49)),
                                    ],
                                    [
                                        new LeafField('id', null, [], [], new Location(1, 66)),
                                    ],
                                    [],
                                    new Location(1, 12)
                                ),
                            ],
                            new Location(1, 10)
                        )
                    ]
                ),
                'mutation { createUser(email: "test@test.com", active: true) { id } }',
            ],
            [
                'mutation { test : createUser (id: 4) }',
                new Document(
                    [
                        new MutationOperation(
                            '',
                            [],
                            [],
                            [
                                new LeafField(
                                    'createUser',
                                    'test',
                                    [
                                        new Argument('id', new Literal(4, new Location(1, 35)), new Location(1, 31)),
                                    ],
                                    [],
                                    new Location(1, 19)
                                ),
                            ],
                            new Location(1, 10)
                        )
                    ]
                ),
                'mutation { test: createUser(id: 4) }',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('queryWithVariablesProvider')]
    public function testQueriesWithVariables(
        string $query,
        Document $document,
        string $documentAsStr
    ): void {
        $parser = $this->getParser();

        // 1st test: Parsing is right
        $this->assertEquals(
            $document,
            $parser->parse($query)
        );

        // 2nd test: Converting document back to query string is right
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    /**
     * @return mixed[]
     */
    public static function queryWithVariablesProvider(): array
    {
        $arrayVariable = new Variable('arrayVariable', 'String', false, true, false, false, false, [], new Location(2, 9));
        $arrayElemRequiredVariable = new Variable('arrayElemRequiredVariable', 'String', false, true, true, false, false, [], new Location(3, 9));
        $arrayOfArraysVariable = new Variable('arrayOfArraysVariable', 'Int', false, true, false, true, false, [], new Location(4, 9));
        $arrayElemRequiredOfArraysVariable = new Variable('arrayElemRequiredOfArraysVariable', 'JSONObject', false, true, true, true, false, [], new Location(5, 9));
        $arrayOfArraysElemRequiredVariable = new Variable('arrayOfArraysElemRequiredVariable', 'ID', false, true, false, true, true, [], new Location(6, 9));
        $arrayElemRequiredOfArraysElemRequiredVariable = new Variable('arrayElemRequiredOfArraysElemRequiredVariable', 'Float', false, true, true, true, true, [], new Location(7, 9));
        $arrayElemRequiredOfArraysElemRequiredRequiredVariable = new Variable('arrayElemRequiredOfArraysElemRequiredRequiredVariable', 'Boolean', true, true, true, true, true, [], new Location(8, 9));
        return [
            [
                <<<GRAPHQL
                    query SomeQuery(
                        \$arrayVariable: [String],
                        \$arrayElemRequiredVariable: [String!],
                        \$arrayOfArraysVariable: [[Int]],
                        \$arrayElemRequiredOfArraysVariable: [[JSONObject]!],
                        \$arrayOfArraysElemRequiredVariable: [[ID!]],
                        \$arrayElemRequiredOfArraysElemRequiredVariable: [[Float!]!],
                        \$arrayElemRequiredOfArraysElemRequiredRequiredVariable: [[Boolean!]!]!,
                    ) {
                        items(
                            findBy1: \$arrayVariable,
                            findBy2: \$arrayElemRequiredVariable,
                            findBy3: \$arrayOfArraysVariable,
                            findBy4: \$arrayElemRequiredOfArraysVariable,
                            findBy5: \$arrayOfArraysElemRequiredVariable,
                            findBy6: \$arrayElemRequiredOfArraysElemRequiredVariable,
                            findBy7: \$arrayElemRequiredOfArraysElemRequiredRequiredVariable,
                        ) {
                            name
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'SomeQuery',
                            [
                                $arrayVariable,
                                $arrayElemRequiredVariable,
                                $arrayOfArraysVariable,
                                $arrayElemRequiredOfArraysVariable,
                                $arrayOfArraysElemRequiredVariable,
                                $arrayElemRequiredOfArraysElemRequiredVariable,
                                $arrayElemRequiredOfArraysElemRequiredRequiredVariable,
                            ],
                            [],
                            [
                                new RelationalField(
                                    'items',
                                    null,
                                    [
                                        new Argument('findBy1', new VariableReference('arrayVariable', $arrayVariable, new Location(11, 22)), new Location(11, 13)),
                                        new Argument('findBy2', new VariableReference('arrayElemRequiredVariable', $arrayElemRequiredVariable, new Location(12, 22)), new Location(12, 13)),
                                        new Argument('findBy3', new VariableReference('arrayOfArraysVariable', $arrayOfArraysVariable, new Location(13, 22)), new Location(13, 13)),
                                        new Argument('findBy4', new VariableReference('arrayElemRequiredOfArraysVariable', $arrayElemRequiredOfArraysVariable, new Location(14, 22)), new Location(14, 13)),
                                        new Argument('findBy5', new VariableReference('arrayOfArraysElemRequiredVariable', $arrayOfArraysElemRequiredVariable, new Location(15, 22)), new Location(15, 13)),
                                        new Argument('findBy6', new VariableReference('arrayElemRequiredOfArraysElemRequiredVariable', $arrayElemRequiredOfArraysElemRequiredVariable, new Location(16, 22)), new Location(16, 13)),
                                        new Argument('findBy7', new VariableReference('arrayElemRequiredOfArraysElemRequiredRequiredVariable', $arrayElemRequiredOfArraysElemRequiredRequiredVariable, new Location(17, 22)), new Location(17, 13)),
                                    ],
                                    [
                                        new LeafField('name', null, [], [], new Location(19, 13)),
                                    ],
                                    [],
                                    new Location(10, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query SomeQuery($arrayVariable: [String], $arrayElemRequiredVariable: [String!], $arrayOfArraysVariable: [[Int]], $arrayElemRequiredOfArraysVariable: [[JSONObject]!], $arrayOfArraysElemRequiredVariable: [[ID!]], $arrayElemRequiredOfArraysElemRequiredVariable: [[Float!]!], $arrayElemRequiredOfArraysElemRequiredRequiredVariable: [[Boolean!]!]!) { items(findBy1: $arrayVariable, findBy2: $arrayElemRequiredVariable, findBy3: $arrayOfArraysVariable, findBy4: $arrayElemRequiredOfArraysVariable, findBy5: $arrayOfArraysElemRequiredVariable, findBy6: $arrayElemRequiredOfArraysElemRequiredVariable, findBy7: $arrayElemRequiredOfArraysElemRequiredRequiredVariable) { name } }',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('queryProvider')]
    public function testQueries(
        string $query,
        Document $document,
        string $documentAsStr,
    ): void {
        $parser = $this->getParser();

        $parsedDocument = $parser->parse($query);

        // 1st test: Parsing is right
        $this->assertEquals(
            $document,
            $parsedDocument
        );

        // 2nd test: Converting document back to query string is right
        $this->assertEquals(
            $documentAsStr,
            $parsedDocument->asDocumentString()
        );
    }

    /**
     * @return mixed[]
     */
    public static function queryProvider(): array
    {
        $filter = new stdClass();
        $filter->title = new Literal('unrequested', new Location(1, 26));
        $filter->director = new Literal('steven', new Location(1, 51));
        $attrs = new stdClass();
        $attrs->stars = new Literal(5, new Location(1, 76));
        $filter->attrs = new InputObject($attrs, new Location(1, 67));

        $on = new stdClass();
        $on->query = new Literal('unrequested', new Location(1, 22));
        return [
            [
                '{ film(id: 1 filmID: 2) { title } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('film', null, [
                                new Argument('id', new Literal(1, new Location(1, 12)), new Location(1, 8)),
                                new Argument('filmID', new Literal(2, new Location(1, 22)), new Location(1, 14)),
                            ], [
                                new LeafField('title', null, [], [], new Location(1, 27)),
                            ], [], new Location(1, 3))
                        ], new Location(1, 1)),
                    ]
                ),
                'query { film(id: 1, filmID: 2) { title } }',
            ],
            [
                '{ test (id: -5) { id } } ',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [
                                new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                            ], [
                                new LeafField('id', null, [], [], new Location(1, 19)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ]
                ),
                'query { test(id: -5) { id } }',
            ],
            [
                "{ test (id: -5) \r\n { id } } ",
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [
                                new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                            ], [
                                new LeafField('id', null, [], [], new Location(2, 4)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ]
                ),
                "query { test(id: -5) { id } }",
            ],
            [
                'query CheckTypeOfLuke {
                  hero(episode: EMPIRE) {
                    __typename,
                    name
                  }
                }',
                new Document(
                    [
                        new QueryOperation('CheckTypeOfLuke', [], [], [
                            new RelationalField('hero', null, [
                                new Argument('episode', new Enum('EMPIRE', new Location(2, 33)), new Location(2, 24)),
                            ], [
                                new LeafField('__typename', null, [], [], new Location(3, 21)),
                                new LeafField('name', null, [], [], new Location(4, 21)),
                            ], [], new Location(2, 19)),
                        ], new Location(1, 7))
                    ]
                ),
                'query CheckTypeOfLuke { hero(episode: EMPIRE) { __typename name } }',
            ],
            [
                '{ test { __typename, id } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [], [
                                new LeafField('__typename', null, [], [], new Location(1, 10)),
                                new LeafField('id', null, [], [], new Location(1, 22)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ]
                ),
                'query { test { __typename id } }',
            ],
            [
                '{}',
                new Document(
                    [
                        new QueryOperation('', [], [], [], new Location(1, 1))
                    ]
                ),
                'query {}',
            ],
            [
                'query test {}',
                new Document(
                    [
                        new QueryOperation('test', [], [], [], new Location(1, 7))
                    ]
                ),
                'query test {}',
            ],
            [
                'query {}',
                new Document(
                    [
                        new QueryOperation('', [], [], [], new Location(1, 7))
                    ]
                ),
                'query {}',
            ],
            [
                'mutation setName { setUserName }',
                new Document(
                    [
                        new MutationOperation('setName', [], [], [
                            new LeafField('setUserName', null, [], [], new Location(1, 20)),
                        ], new Location(1, 10))
                    ]
                ),
                'mutation setName { setUserName }',
            ],
            [
                '{ test { ...userDataFragment } } fragment userDataFragment on User { id, name, email }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [], [new FragmentReference('userDataFragment', new Location(1, 13))], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ],
                    [
                        new Fragment('userDataFragment', 'User', [], [
                            new LeafField('id', null, [], [], new Location(1, 70)),
                            new LeafField('name', null, [], [], new Location(1, 74)),
                            new LeafField('email', null, [], [], new Location(1, 80)),
                        ], new Location(1, 43)),
                    ]
                ),
                <<<GRAPHQL
                query { test { ...userDataFragment } }
                
                fragment userDataFragment on User { id name email }
                GRAPHQL,
            ],
            [
                '{ user (id: 10, name: "max", float: 123.123 ) { id, name } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField(
                                'user',
                                null,
                                [
                                    new Argument('id', new Literal(10, new Location(1, 13)), new Location(1, 9)),
                                    new Argument('name', new Literal('max', new Location(1, 24)), new Location(1, 17)),
                                    new Argument('float', new Literal(123.123, new Location(1, 37)), new Location(1, 30)),
                                ],
                                [
                                    new LeafField('id', null, [], [], new Location(1, 49)),
                                    new LeafField('name', null, [], [], new Location(1, 53)),
                                ],
                                [],
                                new Location(1, 3)
                            ),
                        ], new Location(1, 1))
                    ]
                ),
                'query { user(id: 10, name: "max", float: 123.123) { id name } }',
            ],
            // Block Strings
            'block-strings' => [
                '{ user (id: 10, name: """max""", float: 123.123 ) { id, name } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField(
                                'user',
                                null,
                                [
                                    new Argument('id', new Literal(10, new Location(1, 13)), new Location(1, 9)),
                                    new Argument('name', new Literal('max', new Location(1, 26)), new Location(1, 17)),
                                    new Argument('float', new Literal(123.123, new Location(1, 41)), new Location(1, 34)),
                                ],
                                [
                                    new LeafField('id', null, [], [], new Location(1, 53)),
                                    new LeafField('name', null, [], [], new Location(1, 57)),
                                ],
                                [],
                                new Location(1, 3)
                            ),
                        ], new Location(1, 1))
                    ]
                ),
                'query { user(id: 10, name: "max", float: 123.123) { id name } }',
            ],
            // Block Strings with newlines and commented quotes
            'block-strings-with-newlines' => [
                '{ user (id: 10, name: """
                    Hello,
                      World!
              
                    Yours,
                      GraphQL.
                    \""" Commented quote
                """, float: 123.123 ) { id, name } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField(
                                'user',
                                null,
                                [
                                    new Argument('id', new Literal(10, new Location(1, 13)), new Location(1, 9)),
                                    new Argument('name', new Literal('
                    Hello,
                      World!
              
                    Yours,
                      GraphQL.
                    """ Commented quote
                ', new Location(1, 27)), new Location(1, 17)),
                                    new Argument('float', new Literal(123.123, new Location(8, 29)), new Location(8, 22)),
                                ],
                                [
                                    new LeafField('id', null, [], [], new Location(8, 41)),
                                    new LeafField('name', null, [], [], new Location(8, 45)),
                                ],
                                [],
                                new Location(1, 3)
                            ),
                        ], new Location(1, 1))
                    ]
                ),
                'query { user(id: 10, name: "
                    Hello,
                      World!
              
                    Yours,
                      GraphQL.
                    """ Commented quote
                ", float: 123.123) { id name } }',
            ],
            [
                '{ allUsers : users ( id: [ 1, 2, 3] ) { id } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField(
                                'users',
                                'allUsers',
                                [
                                    new Argument(
                                        'id',
                                        new InputList(
                                            [
                                                new Literal(
                                                    1,
                                                    new Location(1, 28)
                                                ),
                                                new Literal(
                                                    2,
                                                    new Location(1, 31)
                                                ),
                                                new Literal(
                                                    3,
                                                    new Location(1, 34)
                                                ),
                                            ],
                                            new Location(1, 26)
                                        ),
                                        new Location(1, 22)
                                    ),
                                ],
                                [
                                    new LeafField('id', null, [], [], new Location(1, 41)),
                                ],
                                [],
                                new Location(1, 14)
                            ),
                        ], new Location(1, 1))
                    ]
                ),
                'query { allUsers: users(id: [1, 2, 3]) { id } }',
            ],
            [
                '{ allUsers : users ( id: [ 1, 1.5, "2", true, null] ) { id } }',
                new Document(
                    [
                        new QueryOperation(
                            '',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    'allUsers',
                                    [
                                        new Argument(
                                            'id',
                                            new InputList(
                                                [
                                                    new Literal(
                                                        1,
                                                        new Location(1, 28)
                                                    ),
                                                    new Literal(
                                                        1.5,
                                                        new Location(1, 31)
                                                    ),
                                                    new Literal(
                                                        "2",
                                                        new Location(1, 37)
                                                    ),
                                                    new Literal(
                                                        true,
                                                        new Location(1, 41)
                                                    ),
                                                    new Literal(
                                                        null,
                                                        new Location(1, 47)
                                                    ),
                                                ],
                                                new Location(1, 26)
                                            ),
                                            new Location(1, 22)
                                        ),
                                    ],
                                    [
                                        new LeafField('id', null, [], [], new Location(1, 57)),
                                    ],
                                    [],
                                    new Location(1, 14)
                                )
                            ],
                            new Location(1, 1)
                        )
                    ]
                ),
                'query { allUsers: users(id: [1, 1.5, "2", true, null]) { id } }',
            ],
            [
                '{ allUsers : users ( object: { "a": 123, "d": "asd",  "b" : [ 1, 2, 4 ], "c": { "a" : 123, "b":  "asd" } } ) { id } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField(
                                'users',
                                'allUsers',
                                [
                                    new Argument(
                                        'object',
                                        new InputObject(
                                            (object) [
                                                'a' => new Literal(123, new Location(1, 37)),
                                                'd' => new Literal('asd', new Location(1, 48)),
                                                'b' => new InputList(
                                                    [
                                                        new Literal(1, new Location(1, 63)),
                                                        new Literal(2, new Location(1, 66)),
                                                        new Literal(4, new Location(1, 69)),
                                                    ],
                                                    new Location(1, 61)
                                                ),
                                                'c' => new InputObject(
                                                    (object) [
                                                        'a' => new Literal(123, new Location(1, 87)),
                                                        'b' => new Literal('asd', new Location(1, 99)),
                                                    ],
                                                    new Location(1, 79)
                                                ),
                                            ],
                                            new Location(1, 30)
                                        ),
                                        new Location(1, 22)
                                    ),
                                ],
                                [
                                    new LeafField('id', null, [], [], new Location(1, 112)),
                                ],
                                [],
                                new Location(1, 14)
                            ),
                        ], new Location(1, 1))
                    ]
                ),
                'query { allUsers: users(object: {a: 123, d: "asd", b: [1, 2, 4], c: {a: 123, b: "asd"}}) { id } }',
            ],
            [
                '{ films(filter: {title: "unrequested", director: "steven", attrs: { stars: 5 } } ) { title } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('films', null, [
                                new Argument('filter', new InputObject($filter, new Location(1, 17)), new Location(1, 9)),
                            ], [
                                new LeafField('title', null, [], [], new Location(1, 86)),
                            ], [], new Location(1, 3))
                        ], new Location(1, 1)),
                    ]
                ),
                'query { films(filter: {title: "unrequested", director: "steven", attrs: {stars: 5}}) { title } }',
            ],
            // Keywords "query", "on", etc, can also be a fieldArg, directiveArg or objectKey
            [
                '{ films(on: {query: "unrequested"} ) { title @completeWith(fragment: "SomeValue") } }',
                new Document(
                    [
                        new QueryOperation('', [], [], [
                            new RelationalField('films', null, [
                                new Argument('on', new InputObject($on, new Location(1, 13)), new Location(1, 9)),
                            ], [
                                new LeafField('title', null, [], [
                                    new Directive(
                                        'completeWith',
                                        [
                                            new Argument('fragment', new Literal("SomeValue", new Location(1, 71)), new Location(1, 60)),
                                        ],
                                        new Location(1, 47)
                                    )
                                ], new Location(1, 40)),
                            ], [], new Location(1, 3))
                        ], new Location(1, 1)),
                    ]
                ),
                'query { films(on: {query: "unrequested"}) { title @completeWith(fragment: "SomeValue") } }',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('subscriptionProvider')]
    public function testSubscriptions(
        string $query,
        Document $document,
        string $documentAsStr
    ): void {
        $parser = $this->getParser();

        // 1st test: Parsing is right
        $this->assertEquals(
            $document,
            $parser->parse($query)
        );

        // 2nd test: Converting document back to query string is right
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    /**
     * @return mixed[]
     */
    public static function subscriptionProvider(): array
    {
        return [
            [
                'subscription { someStream { id } }',
                new Document(
                    [
                        new SubscriptionOperation(
                            '',
                            [],
                            [],
                            [
                                new RelationalField('someStream', null, [], [
                                    new LeafField('id', null, [], [], new Location(1, 29))
                                ], [], new Location(1, 16)),
                            ],
                            new Location(1, 14)
                        )
                    ]
                ),
                'subscription { someStream { id } }',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('queryWithDirectiveProvider')]
    public function testDirectives(
        string $query,
        Document $document,
        string $documentAsStr
    ): void {
        $parser = $this->getParser();

        // 1st test: Parsing is right
        $this->assertEquals(
            $document,
            $parser->parse($query)
        );

        // 2nd test: Converting document back to query string is right
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    /**
     * @return mixed[]
     */
    public static function queryWithDirectiveProvider(): array
    {
        $formatVariable = new Variable('format', 'String', true, false, false, false, false, [], new Location(1, 24));
        $formatVariable2 = new Variable('format', 'String', true, false, false, false, false, [], new Location(1, 24));
        $limitVariable = new Variable('limit', 'String', true, false, false, false, false, [new Directive('someVariableDirective', [], new Location(1, 41))], new Location(1, 24));
        return [
            // Directive in RelationalField
            [
                <<<GRAPHQL
                    query {
                        users @include(if: true) {
                            name
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            '',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [], new Location(3, 13)),
                                    ],
                                    [
                                        new Directive('include', [
                                            new Argument('if', new Literal(true, new Location(2, 28)), new Location(2, 24)),
                                        ], new Location(2, 16))
                                    ],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query { users @include(if: true) { name } }'
            ],
            // Directive in operation
            [
                <<<GRAPHQL
                    query GetUsersName @someOperationDirective {
                        users {
                            name
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [],
                            [
                                new Directive('someOperationDirective', [], new Location(1, 25))
                            ],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [], new Location(3, 13)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query GetUsersName @someOperationDirective { users { name } }'
            ],
            // Directives in multiple operation
            [
                <<<GRAPHQL
                    query GetUsersName @someOperationDirective {
                        users {
                            name
                        }
                    }
                    query GetPostsTitle @anotherOperationDirective {
                        posts {
                            title
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [],
                            [
                                new Directive('someOperationDirective', [], new Location(1, 25))
                            ],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [], new Location(3, 13)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        ),
                        new QueryOperation(
                            'GetPostsTitle',
                            [],
                            [
                                new Directive('anotherOperationDirective', [], new Location(6, 26))
                            ],
                            [
                                new RelationalField(
                                    'posts',
                                    null,
                                    [],
                                    [
                                        new LeafField('title', null, [], [], new Location(8, 13)),
                                    ],
                                    [],
                                    new Location(7, 9)
                                ),
                            ],
                            new Location(6, 11)
                        )
                    ]
                ),
                'query GetUsersName @someOperationDirective { users { name } }' . PHP_EOL . PHP_EOL . 'query GetPostsTitle @anotherOperationDirective { posts { title } }'
            ],
            // Directive in operation and leaf field
            [
                <<<GRAPHQL
                    query GetUsersName(\$format: String!) @someOperationDirective {
                        users {
                            name @style(format: \$format)
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [
                                $formatVariable
                            ],
                            [
                                new Directive('someOperationDirective', [], new Location(1, 43))
                            ],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [
                                            new Directive('style', [
                                                new Argument('format', new VariableReference('format', $formatVariable, new Location(3, 33)), new Location(3, 25))
                                            ], new Location(3, 19))
                                        ], new Location(3, 13)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query GetUsersName($format: String!) @someOperationDirective { users { name @style(format: $format) } }'
            ],
            // Directive in Variable Definition
            [
                <<<GRAPHQL
                    query GetUsersName(\$limit: String! @someVariableDirective) {
                        users(limit: \$limit) {
                            name
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [
                                $limitVariable,
                            ],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [
                                        new Argument('limit', new VariableReference('limit', $limitVariable, new Location(2, 22)), new Location(2, 15)),
                                    ],
                                    [
                                        new LeafField('name', null, [], [], new Location(3, 13))
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query GetUsersName($limit: String! @someVariableDirective) { users(limit: $limit) { name } }'
            ],
            // Repeatable directives
            [
                <<<GRAPHQL
                    query GetUsersName(\$format: String!) {
                        users {
                            name
                                @style(format: \$format)
                                @someOtherDirective
                                @style(format: \$format)
                                @someOtherDirective
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [
                                $formatVariable2
                            ],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [
                                            new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable2, new Location(4, 32)), new Location(4, 24))], new Location(4, 18)),
                                            new Directive('someOtherDirective', [], new Location(5, 18)),
                                            new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable2, new Location(6, 32)), new Location(6, 24))], new Location(6, 18)),
                                            new Directive('someOtherDirective', [], new Location(7, 18)),
                                        ], new Location(3, 13)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query GetUsersName($format: String!) { users { name @style(format: $format) @someOtherDirective @style(format: $format) @someOtherDirective } }'
            ],
            // Directive in fragment definition
            [
                <<<GRAPHQL
                    query GetUsersName {
                        users {
                            ...UserProps
                        }
                    }

                    fragment UserProps on User @someFragmentDirective {
                        id
                        posts {
                            id
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new FragmentReference('UserProps', new Location(3, 16)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ],
                    [
                        new Fragment('UserProps', 'User', [
                            new Directive('someFragmentDirective', [], new Location(7, 33)),
                        ], [
                            new LeafField('id', null, [], [], new Location(8, 9)),
                            new RelationalField('posts', null, [], [
                                new LeafField('id', null, [], [], new Location(10, 13)),
                            ], [], new Location(9, 9)),
                        ], new Location(7, 14)),
                    ]
                ),
                <<<GRAPHQL
                query GetUsersName { users { ...UserProps } }

                fragment UserProps on User @someFragmentDirective { id posts { id } }
                GRAPHQL,
            ],
            // Directive in fragment
            [
                <<<GRAPHQL
                    query GetUsersName {
                        users {
                            ...UserProps
                        }
                    }

                    fragment UserProps on User {
                        id
                        posts @someOperationDirective {
                            id
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new FragmentReference('UserProps', new Location(3, 16)),
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ],
                    [
                        new Fragment('UserProps', 'User', [], [
                            new LeafField('id', null, [], [], new Location(8, 9)),
                            new RelationalField('posts', null, [], [
                                new LeafField('id', null, [], [], new Location(10, 13)),
                            ], [
                                new Directive('someOperationDirective', [], new Location(9, 16))
                            ], new Location(9, 9)),
                        ], new Location(7, 14)),
                    ]
                ),
                <<<GRAPHQL
                query GetUsersName { users { ...UserProps } }

                fragment UserProps on User { id posts @someOperationDirective { id } }
                GRAPHQL,
            ],
            // Directive in inline fragment
            [
                <<<GRAPHQL
                    query GetUsersName {
                        users {
                            ... on User @outside {
                                id
                                posts @inside {
                                    id
                                }
                            }
                        }
                    }
                GRAPHQL,
                new Document(
                    [
                        new QueryOperation(
                            'GetUsersName',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new InlineFragment(
                                            'User',
                                            [
                                                new LeafField('id', null, [], [], new Location(4, 17)),
                                                new RelationalField('posts', null, [], [
                                                    new LeafField('id', null, [], [], new Location(6, 21)),
                                                    ], [
                                                    new Directive('inside', [], new Location(5, 24))
                                                ], new Location(5, 17))
                                            ],
                                            [
                                                new Directive('outside', [], new Location(3, 26))
                                            ],
                                            new Location(3, 20)
                                        )
                                    ],
                                    [],
                                    new Location(2, 9)
                                ),
                            ],
                            new Location(1, 11)
                        )
                    ]
                ),
                'query GetUsersName { users { ...on User @outside { id posts @inside { id } } } }'
            ],
        ];
    }

    /**
     * @param SplObjectStorage<AstInterface,AstInterface> $astNodeAncestors
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('astNodeAncestorProvider')]
    public function testASTNodeAncestors(
        Document $document,
        SplObjectStorage $astNodeAncestors,
    ): void {
        $this->assertEquals(
            $astNodeAncestors,
            $document->getASTNodeAncestors()
        );
    }

    /**
     * @return mixed[]
     */
    public static function astNodeAncestorProvider(): array
    {
        $astNodeAncestors = [];

        /**
         * Query:
         *
         * { film(id: 1 filmID: 2) { title } }
         */
        $leafField1 = new LeafField('title', null, [], [], new Location(1, 27));
        $literal11 = new Literal(1, new Location(1, 12));
        $argument11 = new Argument('id', $literal11, new Location(1, 8));
        $literal12 = new Literal(2, new Location(1, 22));
        $argument12 = new Argument('filmID', $literal12, new Location(1, 14));
        $relationalField1 = new RelationalField(
            'film',
            null,
            [
                $argument11,
                $argument12,
            ],
            [
                $leafField1,
            ],
            [],
            new Location(1, 3)
        );
        $queryOperation1 = new QueryOperation('', [], [], [
            $relationalField1
        ], new Location(1, 1));
        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $astNodeAncestors1 = new SplObjectStorage();
        $astNodeAncestors1[$literal11] = $argument11;
        $astNodeAncestors1[$literal12] = $argument12;
        $astNodeAncestors1[$argument11] = $relationalField1;
        $astNodeAncestors1[$argument12] = $relationalField1;
        $astNodeAncestors1[$leafField1] = $relationalField1;
        $astNodeAncestors1[$relationalField1] = $queryOperation1;
        $astNodeAncestors[] = [
            new Document(
                [
                    $queryOperation1,
                ]
            ),
            $astNodeAncestors1,
        ];

        /**
         * Query:
         *
         * query GetUsersName(\$format: String!) @someOperationDirective {
         *     users {
         *         name @style(format: \$format)
         *     }
         * }
         */
        $formatVariable2 = new Variable('format', 'String', true, false, false, false, false, [], new Location(1, 24));
        $variableReference2 = new VariableReference('format', $formatVariable2, new Location(3, 33));
        $argument2 = new Argument('format', $variableReference2, new Location(3, 25));
        $directive22 = new Directive(
            'style',
            [
                $argument2
            ],
            new Location(3, 19)
        );
        $leafField2 = new LeafField(
            'name',
            null,
            [],
            [
                $directive22
            ],
            new Location(3, 13)
        );
        $relationalField2 = new RelationalField(
            'users',
            null,
            [],
            [
                $leafField2,
            ],
            [],
            new Location(2, 9)
        );
        $directive21 = new Directive('someOperationDirective', [], new Location(1, 43));
        $queryOperation2 = new QueryOperation(
            'GetUsersName',
            [
                $formatVariable2
            ],
            [
                $directive21
            ],
            [
                $relationalField2,
            ],
            new Location(1, 11)
        );

        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $astNodeAncestors2 = new SplObjectStorage();
        $astNodeAncestors2[$variableReference2] = $argument2;
        $astNodeAncestors2[$argument2] = $directive22;
        $astNodeAncestors2[$directive22] = $leafField2;
        $astNodeAncestors2[$leafField2] = $relationalField2;
        $astNodeAncestors2[$formatVariable2] = $queryOperation2;
        $astNodeAncestors2[$relationalField2] = $queryOperation2;
        $astNodeAncestors2[$directive21] = $queryOperation2;
        $astNodeAncestors[] = [
            new Document(
                [
                    $queryOperation2,
                ]
            ),
            $astNodeAncestors2,
        ];

        /**
         * Query:
         *
         * { test { ...userDataFragment } } fragment userDataFragment on User { id, name, email }
         */
        $leafField31 = new LeafField('id', null, [], [], new Location(1, 70));
        $leafField32 = new LeafField('name', null, [], [], new Location(1, 74));
        $leafField33 = new LeafField('email', null, [], [], new Location(1, 80));
        $fragment3 = new Fragment('userDataFragment', 'User', [], [
            $leafField31,
            $leafField32,
            $leafField33,
        ], new Location(1, 43));
        $fragmentReference3 = new FragmentReference('userDataFragment', new Location(1, 13));
        $relationalField3 = new RelationalField('test', null, [], [$fragmentReference3], [], new Location(1, 3));
        $queryOperation3 = new QueryOperation('', [], [], [
            $relationalField3,
        ], new Location(1, 1));

        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $astNodeAncestors3 = new SplObjectStorage();
        $astNodeAncestors3[$leafField31] = $fragment3;
        $astNodeAncestors3[$leafField32] = $fragment3;
        $astNodeAncestors3[$leafField33] = $fragment3;
        $astNodeAncestors3[$fragmentReference3] = $relationalField3;
        $astNodeAncestors3[$relationalField3] = $queryOperation3;
        $astNodeAncestors[] = [
            new Document(
                [
                    $queryOperation3
                ],
                [
                    $fragment3,
                ]
            ),
            $astNodeAncestors3,
        ];

        /**
         * Query:
         *
         * { allUsers : users ( object: { "a": 123, "d": "asd",  "b" : [ 1, 2, 4 ], "c": { "a" : 123, "b":  "asd" } } ) { id } }
         */
        $literal41 = new Literal(1, new Location(1, 63));
        $literal42 = new Literal(2, new Location(1, 66));
        $literal43 = new Literal(4, new Location(1, 69));
        $inputList41 = new InputList(
            [
                $literal41,
                $literal42,
                $literal43,
            ],
            new Location(1, 61)
        );
        $literal44 = new Literal(123, new Location(1, 87));
        $literal45 = new Literal('asd', new Location(1, 99));
        $inputObject41 = new InputObject(
            (object) [
                'a' => $literal44,
                'b' => $literal45,
            ],
            new Location(1, 79)
        );
        $literal46 = new Literal(123, new Location(1, 37));
        $literal47 = new Literal('asd', new Location(1, 48));
        $inputObject42 = new InputObject(
            (object) [
                'a' => $literal46,
                'd' => $literal47,
                'b' => $inputList41,
                'c' => $inputObject41,
            ],
            new Location(1, 30)
        );
        $argument41 = new Argument(
            'object',
            $inputObject42,
            new Location(1, 22)
        );
        $leafField41 = new LeafField('id', null, [], [], new Location(1, 112));
        $relationalField41 = new RelationalField(
            'users',
            'allUsers',
            [
                $argument41,
            ],
            [
                $leafField41,
            ],
            [],
            new Location(1, 14)
        );
        $queryOperation4 = new QueryOperation('', [], [], [
            $relationalField41,
        ], new Location(1, 1));

        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $astNodeAncestors4 = new SplObjectStorage();
        $astNodeAncestors4[$literal41] = $inputList41;
        $astNodeAncestors4[$literal42] = $inputList41;
        $astNodeAncestors4[$literal43] = $inputList41;
        $astNodeAncestors4[$literal44] = $inputObject41;
        $astNodeAncestors4[$literal45] = $inputObject41;
        $astNodeAncestors4[$literal46] = $inputObject42;
        $astNodeAncestors4[$literal47] = $inputObject42;
        $astNodeAncestors4[$inputList41] = $inputObject42;
        $astNodeAncestors4[$inputObject41] = $inputObject42;
        $astNodeAncestors4[$inputObject42] = $argument41;
        $astNodeAncestors4[$argument41] = $relationalField41;
        $astNodeAncestors4[$leafField41] = $relationalField41;
        $astNodeAncestors4[$relationalField41] = $queryOperation4;
        $astNodeAncestors[] = [
            new Document(
                [
                    $queryOperation4
                ],
            ),
            $astNodeAncestors4,
        ];

        return $astNodeAncestors;
    }

    public function testVariableDefaultValue(): void
    {
        // Test with non-null default value
        $parser          = $this->getParser();
        $document = $parser->parse('
            query ($format: String = "small"){
              user {
                avatar(format: $format)
              }
            }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context());
        $this->assertTrue($var->hasDefaultValue());
        $this->assertEquals('small', $var->getDefaultValue());
        $this->assertEquals('small', $var->getValue());

        // Test with null default value
        $parser          = $this->getParser();
        $document = $parser->parse('
            query ($format: String = null){
              user {
                avatar(format: $format)
              }
            }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context());
        $this->assertTrue($var->hasDefaultValue());
        $this->assertNull($var->getDefaultValue());
        $this->assertNull($var->getValue());

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query ($format: String = null) { user { avatar(format: $format) } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testInputObjectVariableValue(): void
    {
        // Test with default value
        $parser          = $this->getParser();
        $document = $parser->parse('
            query FilterUsers($filter: UserFilterInput = { name: "Pedro", age: 19, relatives: { dad: "Jacinto" } }) {
              users(filter: $filter) {
                id
                name
              }
            }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context());
        $this->assertTrue($var->hasDefaultValue());
        $filter = new stdClass();
        $filter->name = 'Pedro';
        $filter->age = 19;
        $filter->relatives = new stdClass();
        $filter->relatives->dad = 'Jacinto';
        $this->assertEquals($var->getDefaultValue(), $filter);

        // Test injecting in Context
        $parser          = $this->getParser();
        $document = $parser->parse('
        query FilterUsers($filter: UserFilterInput!) {
            users(filter: $filter) {
              id
              name
            }
          }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context(null, ['filter' => $filter]));
        $this->assertFalse($var->hasDefaultValue());
        $this->assertEquals($var->getValue(), $filter);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query FilterUsers($filter: UserFilterInput!) { users(filter: $filter) { id name } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testInputListVariableValue(): void
    {
        // Test with default value
        $parser          = $this->getParser();
        $document = $parser->parse('
            query FilterPosts($ids: [ID!]! = [3, 5, {id: 5}]) {
              posts(ids: $ids) {
                id
                title
              }
            }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context());
        $this->assertTrue($var->hasDefaultValue());
        $idObject = new stdClass();
        $idObject->id = 5;
        $ids = [3, 5, $idObject];
        $this->assertEquals($var->getDefaultValue(), $ids);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query FilterPosts($ids: [ID!]! = [3, 5, {id: 5}]) { posts(ids: $ids) { id title } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );

        // Test injecting in Context
        $parser          = $this->getParser();
        $document = $parser->parse('
        query FilterPosts($ids: [ID!]!) {
            posts(ids: $ids) {
              id
              title
            }
          }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context(null, ['ids' => $ids]));
        $this->assertFalse($var->hasDefaultValue());
        $this->assertEquals($var->getValue(), $ids);

        // 2nd test: Converting document back to query string is right
        $documentAsStr = 'query FilterPosts($ids: [ID!]!) { posts(ids: $ids) { id title } }';
        $this->assertEquals(
            $documentAsStr,
            $document->asDocumentString()
        );
    }

    public function testVariableWithoutValue(): void
    {
        $parser          = $this->getParser();
        $document = $parser->parse('
            query ($format: String){
              user {
                avatar(format: $format)
              }
            }
        ');
        /** @var Variable $var */
        $var = $document->getOperations()[0]->getVariables()[0];
        $var->setContext(new Context());
        $this->assertFalse($var->hasDefaultValue());
    }

    public function testNoDuplicateKeysInInputObjectInVariable(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_6_3, ['name']))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
            query FilterUsers($filter: UserFilterInput = { name: "Pedro", name: "Juancho" }) {
              users(filter: $filter) {
                id
                name
              }
            }
        ');
    }

    public function testNoDuplicateKeysInInputObjectInArgument(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLSpecErrorFeedbackItemProvider::class, GraphQLSpecErrorFeedbackItemProvider::E_5_6_3, ['name']))->getMessage());
        $parser          = $this->getParser();
        $parser->parse('
            query FilterUsers {
              users(filter: { name: "Pedro", name: "Juancho" }) {
                id
                name
              }
            }
        ');
    }

    public function testVariableStartingWithNumber(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, ['NUMBER']))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
          query($1:String){
            posts {
              id
              dateStr(format: $1)
            }
          }
        ');
    }

    public function testVariableReferenceStartingWithNumber(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, ['NUMBER']))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
          query{
            posts {
              id
              dateStr(format: $1)
            }
          }
        ');
    }

    public function testNoSurpassingListModifiersCardinalityInVariables(): void
    {
        $this->expectException(UnsupportedSyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLUnsupportedFeatureErrorFeedbackItemProvider::class, GraphQLUnsupportedFeatureErrorFeedbackItemProvider::E_4))->getMessage());
        $parser = $this->getParser();
        $parser->parse('query ($variable: [[[String]]]){ query ( teas: $variable ) { alias: name } }');
    }

    public function testVariableSyntaxIncorrect(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, ['IDENTIFIER']))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
          query(variable: Int) {
            id
          }
        ');
    }

    public function testVariableNameMissing(): void
    {
        $this->expectException(SyntaxErrorParserException::class);
        $this->expectExceptionMessage((new FeedbackItemResolution(GraphQLParserErrorFeedbackItemProvider::class, GraphQLParserErrorFeedbackItemProvider::E_6, ['COLON']))->getMessage());
        $parser = $this->getParser();
        $parser->parse('
          query($: Int) {
            id
          }
        ');
    }
}
