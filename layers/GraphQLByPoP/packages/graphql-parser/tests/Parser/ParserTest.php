<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Argument;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Field;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Fragment;
use GraphQLByPoP\GraphQLParser\Parser\Ast\FragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Mutation;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Query;
use GraphQLByPoP\GraphQLParser\Parser\Ast\TypedFragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Location;
use GraphQLByPoP\GraphQLParser\Parser\Parser;
use GraphQLByPoP\GraphQLParser\Parser\Token;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{

    public function testEmptyParser()
    {
        $parser = new Parser();

        $this->assertEquals([
            'queryOperations'    => [],
            'mutationOperations' => [],
            'queries'            => [],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ], $parser->parse());
    }

    public function testInvalidSelection()
    {
        $this->expectException(\GraphQLByPoP\GraphQLParser\Exception\Parser\SyntaxErrorException::class);
        $parser = new Parser();
        $data   = $parser->parse('
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

        $parser     = new Parser();
        $parsedData = $parser->parse($query);

        $this->assertEquals($parsedData, [
            'queryOperations'    => [
                [
                    'name' => null,
                    'position' => 0,
                    'numberItems' => 1
                ]
            ],
            'mutationOperations' => [],
            'queries'            => [
                new Query(
                    'authors',
                    null,
                    [
                        new Argument('category', new Literal('#2', new Location(5, 25)), new Location(5, 14)),
                    ],
                    [
                        new Field('_id', null, [], [], new Location(6, 9)),
                    ],
                    [],
                    new Location(5, 5)
                ),
            ],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ]);
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
     * @param $query string
     *
     * @dataProvider wrongQueriesProvider
     */
    public function testWrongQueries($query)
    {
        $this->expectException(\GraphQLByPoP\GraphQLParser\Exception\Parser\SyntaxErrorException::class);
        $parser = new Parser();

        $parser->parse($query);
    }

    public function testCommas()
    {
        $parser = new Parser();
        $data   = $parser->parse('{ foo,       ,,  , bar  }');
        $this->assertEquals([
            new Query('foo', '', [], [], [], new Location(1, 3)),
            new Query('bar', '', [], [], [], new Location(1, 20)),
        ], $data['queries']);
    }

    public function testQueryWithNoFields()
    {
        $parser = new Parser();
        $data   = $parser->parse('{ name }');
        $this->assertEquals([
            'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                new Query('name', '', [], [], [], new Location(1, 3)),
            ],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ], $data);
    }

    public function testQueryWithFields()
    {
        $parser = new Parser();
        $data   = $parser->parse('{ post, user { name } }');
        $this->assertEquals([
            'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                new Query('post', null, [], [], [], new Location(1, 3)),
                new Query('user', null, [], [
                    new Field('name', null, [], [], new Location(1, 16)),
                ], [], new Location(1, 9)),
            ],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ], $data);
    }

    public function testFragmentWithFields()
    {
        $parser = new Parser();
        $data   = $parser->parse('
            fragment FullType on __Type {
                kind
                fields {
                    name
                }
            }');
        $this->assertEquals([
            'queries'            => [],
            'mutations'          => [],
            'fragments'          => [
                new Fragment('FullType', '__Type', [], [
                    new Field('kind', null, [], [], new Location(3, 17)),
                    new Query('fields', null, [], [
                        new Field('name', null, [], [], new Location(5, 21)),
                    ], [], new Location(4, 17)),
                ], new Location(2, 22)),
            ],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
            'queryOperations'    => [],
            'mutationOperations' => [],
        ], $data);
    }

    public function testInspectionQuery()
    {
        $parser = new Parser();

        $data = $parser->parse('
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

        $this->assertEquals([
            'queryOperations'    => [
                [
                    'name' => 'IntrospectionQuery',
                    'position' => 0,
                    'numberItems' => 1
                ]
            ],
            'mutationOperations' => [],
            'queries'            => [
                new Query('__schema', null, [], [
                    new Query('queryType', null, [], [
                        new Field('name', null, [], [], new Location(4, 33)),
                    ], [], new Location(4, 21)),
                    new Query('mutationType', null, [], [
                        new Field('name', null, [], [], new Location(5, 36)),
                    ], [], new Location(5, 21)),
                    new Query('types', null, [], [
                        new FragmentReference('FullType', new Location(7, 28)),
                    ], [], new Location(6, 21)),
                    new Query('directives', null, [], [
                        new Field('name', null, [], [], new Location(10, 25)),
                        new Field('description', null, [], [], new Location(11, 25)),
                        new Query('args', null, [], [
                            new FragmentReference('InputValue', new Location(13, 32)),
                        ], [], new Location(12, 25)),
                        new Field('onOperation', null, [], [], new Location(15, 25)),
                        new Field('onFragment', null, [], [], new Location(16, 25)),
                        new Field('onField', null, [], [], new Location(17, 25)),
                    ], [], new Location(9, 21)),
                ], [], new Location(3, 17)),
            ],
            'mutations'          => [],
            'fragments'          => [
                new Fragment('FullType', '__Type', [], [
                    new Field('kind', null, [], [], new Location(23, 17)),
                    new Field('name', null, [], [], new Location(24, 17)),
                    new Field('description', null, [], [], new Location(25, 17)),
                    new Query('fields', null, [], [
                        new Field('name', null, [], [], new Location(27, 21)),
                        new Field('description', null, [], [], new Location(28, 21)),
                        new Query('args', null, [], [
                            new FragmentReference('InputValue', new Location(30, 28)),
                        ], [], new Location(29, 21)),
                        new Query('type', null, [], [
                            new FragmentReference('TypeRef', new Location(33, 28)),
                        ], [], new Location(32, 21)),
                        new Field('isDeprecated', null, [], [], new Location(35, 21)),
                        new Field('deprecationReason', null, [], [], new Location(36, 21)),
                    ], [], new Location(26, 17)),
                    new Query('inputFields', null, [], [
                        new FragmentReference('InputValue', new Location(39, 24)),
                    ], [], new Location(38, 17)),
                    new Query('interfaces', null, [], [
                        new FragmentReference('TypeRef', new Location(42, 24)),
                    ], [], new Location(41, 17)),
                    new Query('enumValues', null, [], [
                        new Field('name', null, [], [], new Location(45, 21)),
                        new Field('description', null, [], [], new Location(46, 21)),

                        new Field('isDeprecated', null, [], [], new Location(47, 21)),
                        new Field('deprecationReason', null, [], [], new Location(48, 21)),
                    ], [], new Location(44, 17)),
                    new Query('possibleTypes', null, [], [
                        new FragmentReference('TypeRef', new Location(51, 24)),
                    ], [], new Location(50, 17)),
                ], new Location(22, 22)),
                new Fragment('InputValue', '__InputValue', [], [
                    new Field('name', null, [], [], new Location(56, 17)),
                    new Field('description', null, [], [], new Location(57, 17)),
                    new Query('type', null, [], [
                        new FragmentReference('TypeRef', new Location(58, 27)),
                    ], [], new Location(58, 17)),
                    new Field('defaultValue', null, [], [], new Location(59, 17)),
                ], new Location(55, 22)),
                new Fragment('TypeRef', '__Type', [], [
                    new Field('kind', null, [], [], new Location(63, 17)),
                    new Field('name', null, [], [], new Location(64, 17)),
                    new Query('ofType', null, [], [
                        new Field('kind', null, [], [], new Location(66, 21)),
                        new Field('name', null, [], [], new Location(67, 21)),
                        new Query('ofType', null, [], [
                            new Field('kind', null, [], [], new Location(69, 25)),
                            new Field('name', null, [], [], new Location(70, 25)),
                            new Query('ofType', null, [], [
                                new Field('kind', null, [], [], new Location(72, 29)),
                                new Field('name', null, [], [], new Location(73, 29)),
                            ], [], new Location(71, 25)),
                        ], [], new Location(68, 21)),
                    ], [], new Location(65, 17)),
                ], new Location(62, 22)),
            ],
            'fragmentReferences' => [
                new FragmentReference('FullType', new Location(7, 28)),
                new FragmentReference('InputValue', new Location(13, 32)),
                new FragmentReference('InputValue', new Location(30, 28)),
                new FragmentReference('TypeRef', new Location(33, 28)),
                new FragmentReference('InputValue', new Location(39, 24)),
                new FragmentReference('TypeRef', new Location(42, 24)),
                new FragmentReference('TypeRef', new Location(51, 24)),
                new FragmentReference('TypeRef', new Location(58, 27)),
            ],
            'variables'          => [],
            'variableReferences' => [],
        ], $data);
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

    /**
     * @dataProvider mutationProvider
     */
    public function testMutations($query, $structure)
    {
        $parser = new Parser();

        $parsedStructure = $parser->parse($query);

        $this->assertEquals($parsedStructure, $structure);
    }

    public function testTypedFragment()
    {
        $parser          = new Parser();
        $parsedStructure = $parser->parse('
            {
                test: test {
                    name,
                    ... on UnionType {
                        unionName
                    }
                }
            }
        ');

        $this->assertEquals($parsedStructure, [
            'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                new Query(
                    'test',
                    'test',
                    [],
                    [
                        new Field('name', null, [], [], new Location(4, 21)),
                        new TypedFragmentReference('UnionType', [new Field('unionName', null, [], [], new Location(6, 25))], [], new Location(5, 28)),
                    ],
                    [],
                    new Location(3, 23)
                ),
            ],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ]);
    }

    public function mutationProvider()
    {
        return [
            [
                'query ($variable: Int){ query ( teas: $variable ) { alias: name } }',
                [
                    'queryOperations'    => [
                        [
                            'name' => null,
                            'position' => 0,
                            'numberItems' => 1
                        ]
                    ],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query(
                            'query',
                            null,
                            [
                                new Argument('teas', new VariableReference('variable', (new Variable('variable', 'Int', false, false, true, new Location(1, 8)))->setUsed(true), new Location(1, 39)), new Location(1, 33)),
                            ],
                            [
                                new Field('name', 'alias', [], [], new Location(1, 60)),
                            ],
                            [],
                            new Location(1, 25)
                        ),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [
                        (new Variable('variable', 'Int', false, false, true, new Location(1, 8)))->setUsed(true),
                    ],
                    'variableReferences' => [
                        new VariableReference('variable', (new Variable('variable', 'Int', false, false, true, new Location(1, 8)))->setUsed(true), new Location(1, 39)),
                    ],
                ],
            ],
            [
                '{ query { alias: name } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('query', null, [], [new Field('name', 'alias', [], [], new Location(1, 18))], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'mutation { createUser ( email: "test@test.com", active: true ) { id } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [],
                    'mutations'          => [
                        new Mutation(
                            'createUser',
                            null,
                            [
                                new Argument('email', new Literal('test@test.com', new Location(1, 33)), new Location(1, 25)),
                                new Argument('active', new Literal(true, new Location(1, 57)), new Location(1, 49)),
                            ],
                            [
                                new Field('id', null, [], [], new Location(1, 66)),
                            ],
                            [],
                            new Location(1, 12)
                        ),
                    ],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'mutation { test : createUser (id: 4) }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [],
                    'mutations'          => [
                        new Mutation(
                            'createUser',
                            'test',
                            [
                                new Argument('id', new Literal(4, new Location(1, 35)), new Location(1, 31)),
                            ],
                            [],
                            [],
                            new Location(1, 19)
                        ),
                    ],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider queryProvider
     */
    public function testParser($query, $structure)
    {
        $parser          = new Parser();
        $parsedStructure = $parser->parse($query);

        $this->assertEquals($structure, $parsedStructure);
    }


    public function queryProvider()
    {
        return [
            [
                '{ film(id: 1 filmID: 2) { title } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('film', null, [
                            new Argument('id', new Literal(1, new Location(1, 12)), new Location(1, 8)),
                            new Argument('filmID', new Literal(2, new Location(1, 22)), new Location(1, 14)),
                        ], [
                            new Field('title', null, [], [], new Location(1, 27)),
                        ], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ test (id: -5) { id } } ',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('test', null, [
                            new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                        ], [
                            new Field('id', null, [], [], new Location(1, 19)),
                        ], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                "{ test (id: -5) \r\n { id } } ",
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('test', null, [
                            new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                        ], [
                            new Field('id', null, [], [], new Location(2, 4)),
                        ], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'query CheckTypeOfLuke {
                  hero(episode: EMPIRE) {
                    __typename,
                    name
                  }
                }',
                [
                    'queryOperations'    => [
                        [
                            'name' => 'CheckTypeOfLuke',
                            'position' => 0,
                            'numberItems' => 1
                        ]
                    ],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('hero', null, [
                            new Argument('episode', new Literal('EMPIRE', new Location(2, 33)), new Location(2, 24)),
                        ], [
                            new Field('__typename', null, [], [], new Location(3, 21)),
                            new Field('name', null, [], [], new Location(4, 21)),
                        ], [], new Location(2, 19)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ test { __typename, id } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('test', null, [], [
                            new Field('__typename', null, [], [], new Location(1, 10)),
                            new Field('id', null, [], [], new Location(1, 22)),
                        ], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{}',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'query test {}',
                [
                    'queryOperations'    => [
                        [
                            'name' => 'test',
                            'position' => 0,
                            'numberItems' => 0
                        ]
                    ],
                    'mutationOperations' => [],
                    'queries'            => [],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'query {}',
                [
                    'queryOperations'    => [
                        [
                            'name' => null,
                            'position' => 0,
                            'numberItems' => 0
                        ]
                    ],
                    'mutationOperations' => [],
                    'queries'            => [],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                'mutation setName { setUserName }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [
                        [
                            'name' => 'setName',
                            'position' => 0,
                            'numberItems' => 1
                        ]
                    ],
                    'queries'            => [],
                    'mutations'          => [new Mutation('setUserName', null, [], [], [], new Location(1, 20))],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ test { ...userDataFragment } } fragment userDataFragment on User { id, name, email }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query('test', null, [], [new FragmentReference('userDataFragment', new Location(1, 13))], [], new Location(1, 3)),
                    ],
                    'mutations'          => [],
                    'fragments'          => [
                        new Fragment('userDataFragment', 'User', [], [
                            new Field('id', null, [], [], new Location(1, 70)),
                            new Field('name', null, [], [], new Location(1, 74)),
                            new Field('email', null, [], [], new Location(1, 80)),
                        ], new Location(1, 43)),
                    ],
                    'fragmentReferences' => [
                        new FragmentReference('userDataFragment', new Location(1, 13)),
                    ],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ user (id: 10, name: "max", float: 123.123 ) { id, name } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query(
                            'user',
                            null,
                            [
                                new Argument('id', new Literal('10', new Location(1, 13)), new Location(1, 9)),
                                new Argument('name', new Literal('max', new Location(1, 24)), new Location(1, 17)),
                                new Argument('float', new Literal('123.123', new Location(1, 37)), new Location(1, 30)),
                            ],
                            [
                                new Field('id', null, [], [], new Location(1, 49)),
                                new Field('name', null, [], [], new Location(1, 53)),
                            ],
                            [],
                            new Location(1, 3)
                        ),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ allUsers : users ( id: [ 1, 2, 3] ) { id } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query(
                            'users',
                            'allUsers',
                            [
                                new Argument('id', new InputList([1, 2, 3], new Location(1, 26)), new Location(1, 22)),
                            ],
                            [
                                new Field('id', null, [], [], new Location(1, 41)),
                            ],
                            [],
                            new Location(1, 14)
                        ),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ allUsers : users ( id: [ 1, "2", true, null] ) { id } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query(
                            'users',
                            'allUsers',
                            [
                                new Argument('id', new InputList([1, "2", true, null], new Location(1, 26)), new Location(1, 22)),
                            ],
                            [
                                new Field('id', null, [], [], new Location(1, 52)),
                            ],
                            [],
                            new Location(1, 14)
                        ),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
            [
                '{ allUsers : users ( object: { "a": 123, "d": "asd",  "b" : [ 1, 2, 4 ], "c": { "a" : 123, "b":  "asd" } } ) { id } }',
                [
                    'queryOperations'    => [],
                    'mutationOperations' => [],
                    'queries'            => [
                        new Query(
                            'users',
                            'allUsers',
                            [
                                new Argument('object', new InputObject([
                                    'a' => 123,
                                    'd' => 'asd',
                                    'b' => [1, 2, 4],
                                    'c' => new InputObject([
                                        'a' => 123,
                                        'b' => 'asd',
                                    ], new Location(1, 79)),
                                ], new Location(1, 30)), new Location(1, 22)),
                            ],
                            [
                                new Field('id', null, [], [], new Location(1, 112)),
                            ],
                            [],
                            new Location(1, 14)
                        ),
                    ],
                    'mutations'          => [],
                    'fragments'          => [],
                    'fragmentReferences' => [],
                    'variables'          => [],
                    'variableReferences' => [],
                ],
            ],
        ];
    }

    public function testVariablesInQuery()
    {
        $parser = new Parser();

        $data = $parser->parse('
            query StarWarsAppHomeRoute($names_0:[String!]!, $query: String) {
              factions(names:$names_0, test: $query) {
                id,
                ...F2
              }
            }
            fragment F0 on Ship {
              id,
              name
            }
            fragment F1 on Faction {
              id,
              factionId
            }
            fragment F2 on Faction {
              id,
              factionId,
              name,
              _shipsDRnzJ:ships(first:10) {
                edges {
                  node {
                    id,
                    ...F0
                  },
                  cursor
                },
                pageInfo {
                  hasNextPage,
                  hasPreviousPage
                }
              },
              ...F1
            }
        ');

        $this->assertArrayNotHasKey('errors', $data);
    }

    public function testVariableDefaultValue()
    {
        // Test with non-null default value
        $parser          = new Parser();
        $parsedStructure = $parser->parse('
            query ($format: String = "small"){
              user {
                avatar(format: $format)
              }
            }
        ');
        /** @var Variable $var */
        $var = $parsedStructure['variables'][0];
        $this->assertTrue($var->hasDefaultValue());
        $this->assertEquals('small', $var->getDefaultValue()->getValue());
        $this->assertEquals('small', $var->getValue()->getValue());

        // Test with null default value
        $parser          = new Parser();
        $parsedStructure = $parser->parse('
            query ($format: String = null){
              user {
                avatar(format: $format)
              }
            }
        ');
        /** @var Variable $var */
        $var = $parsedStructure['variables'][0];
        $this->assertTrue($var->hasDefaultValue());
        $this->assertNull($var->getDefaultValue()->getValue());
        $this->assertNull($var->getValue()->getValue());
    }
}
