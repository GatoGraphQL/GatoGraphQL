<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PHPUnit\Framework\TestCase;
use PoPBackbone\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\MutationOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Ast\TypedFragmentReference;

class ParserTest extends TestCase
{

    public function testEmptyParser()
    {
        $parser = new Parser();
        $document = $parser->parse('');

        $this->assertEquals([
            'operations'    => [],
            'fragments'          => [],
        ], $document->toArray());
    }

    public function testInvalidSelection()
    {
        $this->expectException(SyntaxErrorException::class);
        $parser = new Parser();
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

        $parser     = new Parser();
        $document = $parser->parse($query);

        $this->assertEquals($document->toArray(), [
            'operations'            => [
                new QueryOperation('', [], [],
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
            ],
            'fragments'          => [],
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
     * @dataProvider wrongQueriesProvider
     */
    public function testWrongQueries(string $query)
    {
        $this->expectException(SyntaxErrorException::class);
        $parser = new Parser();

        $parser->parse($query);
    }

    public function testCommas()
    {
        $parser = new Parser();
        $document   = $parser->parse('{ foo,       ,,  , bar  }');
        $this->assertEquals([
            new QueryOperation('', [], [],
                [
                    new LeafField('foo', '', [], [], new Location(1, 3)),
                    new LeafField('bar', '', [], [], new Location(1, 20)),
                ],
                new Location(1, 1)
            )
        ], $document->toArray()['operations']);
    }

    public function testQueryWithNoFields()
    {
        $parser = new Parser();
        $document   = $parser->parse('{ name }');
        $this->assertEquals([
            'operations'            => [
                new QueryOperation('', [], [],
                    [
                        new LeafField('name', '', [], [], new Location(1, 3)),
                    ],
                    new Location(1, 1)
                )
            ],
            'fragments'          => [],
        ], $document->toArray());
    }

    public function testQueryWithFields()
    {
        $parser = new Parser();
        $document   = $parser->parse('{ post, user { name } }');
        $this->assertEquals([
            'operations'            => [
                new QueryOperation('', [], [],
                    [
                        new LeafField('post', null, [], [], new Location(1, 3)),
                        new RelationalField('user', null, [], [
                            new LeafField('name', null, [], [], new Location(1, 16)),
                        ], [], new Location(1, 9)),
                    ],
                    new Location(1, 1)
                )
            ],
            'fragments'          => [],
        ], $document->toArray());
    }

    public function testFragmentWithFields()
    {
        $parser = new Parser();
        $document   = $parser->parse('
            fragment FullType on __Type {
                kind
                fields {
                    name
                }
            }');
        $this->assertEquals([
            'operations'    => [],
            'fragments'          => [
                new Fragment('FullType', '__Type', [], [
                    new LeafField('kind', null, [], [], new Location(3, 17)),
                    new RelationalField('fields', null, [], [
                        new LeafField('name', null, [], [], new Location(5, 21)),
                    ], [], new Location(4, 17)),
                ], new Location(2, 22)),
            ],
        ], $document->toArray());
    }

    public function testInspectionQuery()
    {
        $parser = new Parser();

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

        $this->assertEquals([
            'operations'            => [
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
            'fragments'          => [
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
            ],
        ], $document->toArray());
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

        $document = $parser->parse($query);

        $this->assertEquals($document->toArray(), $structure);
    }

    public function testTypedFragment()
    {
        $parser          = new Parser();
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

        $this->assertEquals($document->toArray(), [
            'operations'            => [
                new QueryOperation('', [], [],
                    [
                        new RelationalField(
                            'test',
                            'test',
                            [],
                            [
                                new LeafField('name', null, [], [], new Location(4, 21)),
                                new TypedFragmentReference('UnionType', [new LeafField('unionName', null, [], [], new Location(6, 25))], [], new Location(5, 28)),
                            ],
                            [],
                            new Location(3, 23)
                        ),
                    ],
                    new Location(2, 13)
                )
            ],
            'fragments'          => [],
        ]);
    }

    public function mutationProvider()
    {
        $variable = new Variable('variable', 'Int', false, false, false, new Location(1, 8));
        return [
            [
                'query ($variable: Int){ query ( teas: $variable ) { alias: name } }',
                [
                    'operations'            => [
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
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ query { alias: name } }',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('query', null, [], [new LeafField('name', 'alias', [], [], new Location(1, 18))], [], new Location(1, 3)),
                        ], new Location(1, 1)),
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                'mutation { createUser ( email: "test@test.com", active: true ) { id } }',
                [
                    'operations'          => [
                        new MutationOperation('', [], [],
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
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                'mutation { test : createUser (id: 4) }',
                [
                    'operations'            => [
                        new MutationOperation('', [], [],
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
                    ],
                    'fragments'          => [],
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
        $document = $parser->parse($query);

        $this->assertEquals($structure, $document->toArray());
    }


    public function queryProvider()
    {
        return [
            [
                '{ film(id: 1 filmID: 2) { title } }',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('film', null, [
                                new Argument('id', new Literal(1, new Location(1, 12)), new Location(1, 8)),
                                new Argument('filmID', new Literal(2, new Location(1, 22)), new Location(1, 14)),
                            ], [
                                new LeafField('title', null, [], [], new Location(1, 27)),
                            ], [], new Location(1, 3))
                        ], new Location(1, 1)),
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ test (id: -5) { id } } ',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [
                                new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                            ], [
                                new LeafField('id', null, [], [], new Location(1, 19)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                "{ test (id: -5) \r\n { id } } ",
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [
                                new Argument('id', new Literal(-5, new Location(1, 13)), new Location(1, 9)),
                            ], [
                                new LeafField('id', null, [], [], new Location(2, 4)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ],
                    'fragments'          => [],
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
                    'operations'            => [
                        new QueryOperation('CheckTypeOfLuke', [], [], [
                            new RelationalField('hero', null, [
                                new Argument('episode', new Literal('EMPIRE', new Location(2, 33)), new Location(2, 24)),
                            ], [
                                new LeafField('__typename', null, [], [], new Location(3, 21)),
                                new LeafField('name', null, [], [], new Location(4, 21)),
                            ], [], new Location(2, 19)),
                        ], new Location(1, 7))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ test { __typename, id } }',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [], [
                                new LeafField('__typename', null, [], [], new Location(1, 10)),
                                new LeafField('id', null, [], [], new Location(1, 22)),
                            ], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{}',
                [
                    'operations'    => [
                        new QueryOperation('', [], [], [], new Location(1, 1))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                'query test {}',
                [
                    'operations'    => [
                        new QueryOperation('test', [], [], [], new Location(1, 7))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                'query {}',
                [
                    'operations'    => [
                        new QueryOperation('', [], [], [], new Location(1, 7))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                'mutation setName { setUserName }',
                [
                    'operations'            => [
                        new MutationOperation('setName', [], [], [
                            new LeafField('setUserName', null, [], [], new Location(1, 20)),
                        ], new Location(1, 10))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ test { ...userDataFragment } } fragment userDataFragment on User { id, name, email }',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
                            new RelationalField('test', null, [], [new FragmentReference('userDataFragment', new Location(1, 13))], [], new Location(1, 3)),
                        ], new Location(1, 1))
                    ],
                    'fragments'          => [
                        new Fragment('userDataFragment', 'User', [], [
                            new LeafField('id', null, [], [], new Location(1, 70)),
                            new LeafField('name', null, [], [], new Location(1, 74)),
                            new LeafField('email', null, [], [], new Location(1, 80)),
                        ], new Location(1, 43)),
                    ],
                ],
            ],
            [
                '{ user (id: 10, name: "max", float: 123.123 ) { id, name } }',
                [
                    'operations'            => [
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
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ allUsers : users ( id: [ 1, 2, 3] ) { id } }',
                [
                    'operations'            => [
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
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ allUsers : users ( id: [ 1, "2", true, null] ) { id } }',
                [
                    'operations'            => [
                        new QueryOperation('', [], [], [
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
                        ], new Location(1, 1))
                    ],
                    'fragments'          => [],
                ],
            ],
            [
                '{ allUsers : users ( object: { "a": 123, "d": "asd",  "b" : [ 1, 2, 4 ], "c": { "a" : 123, "b":  "asd" } } ) { id } }',
                [
                    'operations'            => [
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
                    ],
                    'fragments'          => [],
                ],
            ],
        ];
    }

    public function testVariablesInQuery()
    {
        $parser = new Parser();

        $document = $parser->parse('
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

        $this->assertArrayNotHasKey('errors', $document->toArray());
    }

    public function testVariableDefaultValue()
    {
        // Test with non-null default value
        $parser          = new Parser();
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
        $parser          = new Parser();
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
}
