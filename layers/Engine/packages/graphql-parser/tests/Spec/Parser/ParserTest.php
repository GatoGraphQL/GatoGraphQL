<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\Root\AbstractTestCase;
use stdClass;

class ParserTest extends AbstractTestCase
{
    protected function getParser(): ParserInterface
    {
        // return $this->getService(ParserInterface::class);
        return new Parser();
    }

    public function testEmptyParser()
    {
        $parser = $this->getParser();
        $document = $parser->parse('');

        $this->assertEquals(new Document([], []), $document);
    }

    public function testInvalidSelection()
    {
        $this->expectException(SyntaxErrorException::class);
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

    public function testComments()
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
    }

    private function tokenizeStringContents($graphQLString)
    {
        $parser = new TokenizerTestingParser();
        $parser->initTokenizerForTesting('"' . $graphQLString . '"');

        return $parser->getTokenForTesting();
    }


    public function testEscapedStrings()
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

    /**
     * @dataProvider wrongQueriesProvider
     */
    public function testWrongQueries(string $query)
    {
        $this->expectException(SyntaxErrorException::class);
        $parser = $this->getParser();

        $parser->parse($query);
    }

    public function testCommas()
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
    }

    public function testQueryWithNoFields()
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
    }

    public function testQueryWithFields()
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
    }

    public function testFragmentWithFields()
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
    }

    public function testInspectionQuery()
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

    public function wrongQueriesProvider()
    {
        return [
            ['{ test (a: "asd", b: <basd>) { id }'],
            ['{ test (asd: [..., asd]) { id } }'],
            ['{ test (asd: { "a": 4, "m": null, "asd": false  "b": 5, "c" : { a }}) { id } }'],
            ['asdasd'],
            ['mutation { test(asd: ... ){ ...,asd, asd } }'],
            ['mutation { test{ . test on Test { id } } }'],
            ['mutation { test( a: "asdd'],
            ['mutation { test( a: { "asd": 12 12'],
            ['mutation { test( a: { "asd": 12'],
        ];
    }

    public function testInlineFragment()
    {
        $parser          = $this->getParser();
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
    }

    /**
     * @dataProvider mutationProvider
     */
    public function testMutations($query, $structure)
    {
        $parser = $this->getParser();

        $document = $parser->parse($query);

        $this->assertEquals($document, $structure);
    }

    public function mutationProvider()
    {
        $variable = new Variable('variable', 'Int', false, false, false, new Location(1, 8));
        return [
            [
                'query ($variable: Int){ query ( teas: $variable ) { alias: name } }',
                new Document([
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
                    ]),
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
            ],
        ];
    }

    /**
     * @dataProvider queryProvider
     */
    public function testParser($query, $structure)
    {
        $parser          = $this->getParser();
        $document = $parser->parse($query);

        $this->assertEquals($structure, $document);
    }

    public function queryProvider()
    {
        $filter = new stdClass();
        $filter->title = 'unrequested';
        $filter->director = 'steven';
        $attrs = new stdClass();
        $attrs->stars = 5;
        $filter->attrs = new InputObject($attrs, new Location(1, 67));
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
                                new Argument('episode', new Literal('EMPIRE', new Location(2, 33)), new Location(2, 24)),
                            ], [
                                new LeafField('__typename', null, [], [], new Location(3, 21)),
                                new LeafField('name', null, [], [], new Location(4, 21)),
                            ], [], new Location(2, 19)),
                        ], new Location(1, 7))
                    ]
                ),
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
            ],
            [
                '{}',
                new Document(
                    [
                        new QueryOperation('', [], [], [], new Location(1, 1))
                    ]
                ),
            ],
            [
                'query test {}',
                new Document(
                    [
                        new QueryOperation('test', [], [], [], new Location(1, 7))
                    ]
                ),
            ],
            [
                'query {}',
                new Document(
                    [
                        new QueryOperation('', [], [], [], new Location(1, 7))
                    ]
                ),
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
                                    new Argument('id', new Literal('10', new Location(1, 13)), new Location(1, 9)),
                                    new Argument('name', new Literal('max', new Location(1, 24)), new Location(1, 17)),
                                    new Argument('float', new Literal('123.123', new Location(1, 37)), new Location(1, 30)),
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
                                    new Argument('id', new InputList([1, 2, 3], new Location(1, 26)), new Location(1, 22)),
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
            ],
            [
                '{ allUsers : users ( id: [ 1, "2", true, null] ) { id } }',
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
                                        new Argument('id', new InputList([1, "2", true, null], new Location(1, 26)), new Location(1, 22)),
                                    ],
                                    [
                                        new LeafField('id', null, [], [], new Location(1, 52)),
                                    ],
                                    [],
                                    new Location(1, 14)
                                )
                            ],
                            new Location(1, 1)
                        )
                    ]
                ),
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
                                    new Argument('object', new InputObject((object) [
                                        'a' => 123,
                                        'd' => 'asd',
                                        'b' => [1, 2, 4],
                                        'c' => new InputObject((object) [
                                            'a' => 123,
                                            'b' => 'asd',
                                        ], new Location(1, 79)),
                                    ], new Location(1, 30)), new Location(1, 22)),
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
            ],
        ];
    }

    /**
     * @dataProvider queryWithDirectiveProvider
     */
    public function testDirectives($query, $structure)
    {
        $parser = $this->getParser();

        $document = $parser->parse($query);

        $this->assertEquals($document, $structure);
    }

    public function queryWithDirectiveProvider()
    {
        $formatVariable = new Variable('format', 'String', true, false, false, new Location(1, 24));
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
                                $formatVariable
                            ],
                            [],
                            [
                                new RelationalField(
                                    'users',
                                    null,
                                    [],
                                    [
                                        new LeafField('name', null, [], [
                                            new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable, new Location(4, 32)), new Location(4, 24))], new Location(4, 18)),
                                            new Directive('someOtherDirective', [], new Location(5, 18)),
                                            new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable, new Location(6, 32)), new Location(6, 24))], new Location(6, 18)),
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
            ],
        ];
    }

    public function testVariableDefaultValue()
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
        $this->assertEquals('small', $var->getDefaultValue()->getValue());
        $this->assertEquals('small', $var->getValue()->getValue());

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
        $this->assertNull($var->getDefaultValue()->getValue());
        $this->assertNull($var->getValue()->getValue());
    }

    public function testInputObjectVariableValue()
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
        $this->assertEquals($var->getDefaultValue()->getValue(), $filter);

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
        $this->assertEquals($var->getValue()->getValue(), $filter);
    }

    public function testInputListVariableValue()
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
        $this->assertEquals($var->getDefaultValue()->getValue(), $ids);

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
        $this->assertEquals($var->getValue()->getValue(), $ids);
    }

    public function testNoDuplicateKeysInInputObjectInVariable()
    {
        $this->expectException(SyntaxErrorException::class);
        $parser          = $this->getParser();
        $parser->parse('
            query FilterUsers($filter: UserFilterInput = { name: "Pedro", name: "Juancho" }) {
              users(filter: $filter) {
                id
                name
              }
            }
        ');
    }

    public function testNoDuplicateKeysInInputObjectInArgument()
    {
        $this->expectException(SyntaxErrorException::class);
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
}
