<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\ComponentModel\GraphQLParser\Parser\ExtendedParser;
use PoP\Root\AbstractTestCase;
use PoP\GraphQLParser\Spec\Parser\ParserTest;

/**
 * Execute the tests from the UpstreamParserTest, but using the ExtendedParser instead
 */
abstract class ExtendedParserTest extends AbstractTestCase
{
    protected static ParserTest $parserTest;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$parserTest = new ParserTest();
        self::$parserTest->setParser(new ExtendedParser());
    }

    public function testEmptyParser(): void
    {
        self::$parserTest->testEmptyParser();
    }

    public function testInvalidSelection(): void
    {
        self::$parserTest->testInvalidSelection();
    }

    public function testComments(): void
    {
        self::$parserTest->testComments();
    }

    public function testEscapedStrings(): void
    {
        self::$parserTest->testEscapedStrings();
    }

    public function testWrongQueries(string $query): void
    {
        self::$parserTest->testWrongQueries($query);
    }

    public function testCommas(): void
    {
        self::$parserTest->testCommas();
    }

    public function testQueryWithNoFields(): void
    {
        self::$parserTest->testQueryWithNoFields();
    }

    public function testQueryWithFields(): void
    {
        self::$parserTest->testQueryWithFields();
    }

    public function testFragmentWithFields(): void
    {
        self::$parserTest->testFragmentWithFields();
    }

    public function testInspectionQuery(): void
    {
        self::$parserTest->testInspectionQuery();
    }

    public function testInlineFragment(): void
    {
        self::$parserTest->testInlineFragment();
    }

    public function testMutations($query, $structure): void
    {
        self::$parserTest->testMutations($query, $structure);
    }

    public function testParser($query, $structure): void
    {
        self::$parserTest->testParser($query, $structure);
    }

    public function testDirectives($query, $structure): void
    {
        self::$parserTest->testDirectives($query, $structure);
    }

    public function testVariableDefaultValue(): void
    {
        self::$parserTest->testVariableDefaultValue();
    }

    public function testInputObjectVariableValue(): void
    {
        self::$parserTest->testInputObjectVariableValue();
    }

    public function testInputListVariableValue(): void
    {
        self::$parserTest->testInputListVariableValue();
    }

    public function testNoDuplicateKeysInInputObjectInVariable(): void
    {
        self::$parserTest->testNoDuplicateKeysInInputObjectInVariable();
    }

    public function testNoDuplicateKeysInInputObjectInArgument(): void
    {
        self::$parserTest->testNoDuplicateKeysInInputObjectInArgument();
    }


    // protected function getParser(): Parser
    // {
    //     $parser = new ExtendedParser();

    //     return $parser;
    // }

    // /**
    //  * @dataProvider queryWithMetaDirectiveProvider
    //  */
    // public function testMetaDirectives($query, $structure)
    // {
    //     $parser = $this->getParser();

    //     $document = $parser->parse($query);

    //     $this->assertEquals($document, $structure);
    // }

    // public function queryWithMetaDirectiveProvider()
    // {
    //     return [
    //         [
    //             <<<GRAPHQL
    //                 query {
    //                     capabilities @forEach @upperCase
    //                 }
    //             GRAPHQL,
    //             new Document(
    //                 [
    //                     new QueryOperation(
    //                         '',
    //                         [],
    //                         [],
    //                         [
    //                             new LeafField(
    //                                 'capabilities',
    //                                 null,
    //                                 [],
    //                                 [
    //                                     new MetaDirective(
    //                                         'forEach',
    //                                         [
    //                                             // new Argument('if', new Literal(true, new Location(2, 28)), new Location(2, 24)),
    //                                         ],
    //                                         [
    //                                             new Directive('upperCase', [], new Location(2, 16)),
    //                                         ],
    //                                         new Location(2, 16)
    //                                     )
    //                                 ],
    //                                 new Location(2, 9)
    //                             ),
    //                         ],
    //                         new Location(1, 11)
    //                     )
    //                 ]
    //             ),
    //         ],
    //         // // Directive in operation
    //         // [
    //         //     <<<GRAPHQL
    //         //         query GetUsersName @someOperationDirective {
    //         //             users {
    //         //                 name
    //         //             }
    //         //         }
    //         //     GRAPHQL,
    //         //     new Document(
    //         //         [
    //         //             new QueryOperation(
    //         //                 'GetUsersName',
    //         //                 [],
    //         //                 [
    //         //                     new Directive('someOperationDirective', [], new Location(1, 25))
    //         //                 ],
    //         //                 [
    //         //                     new RelationalField(
    //         //                         'users',
    //         //                         null,
    //         //                         [],
    //         //                         [
    //         //                             new LeafField('name', null, [], [], new Location(3, 13)),
    //         //                         ],
    //         //                         [],
    //         //                         new Location(2, 9)
    //         //                     ),
    //         //                 ],
    //         //                 new Location(1, 11)
    //         //             )
    //         //         ]
    //         //     ),
    //         // ],
    //         // // Directive in operation and leaf field
    //         // [
    //         //     <<<GRAPHQL
    //         //         query GetUsersName(\$format: String!) @someOperationDirective {
    //         //             users {
    //         //                 name @style(format: \$format)
    //         //             }
    //         //         }
    //         //     GRAPHQL,
    //         //     new Document(
    //         //         [
    //         //             new QueryOperation(
    //         //                 'GetUsersName',
    //         //                 [
    //         //                     $formatVariable
    //         //                 ],
    //         //                 [
    //         //                     new Directive('someOperationDirective', [], new Location(1, 43))
    //         //                 ],
    //         //                 [
    //         //                     new RelationalField(
    //         //                         'users',
    //         //                         null,
    //         //                         [],
    //         //                         [
    //         //                             new LeafField('name', null, [], [
    //         //                                 new Directive('style', [
    //         //                                     new Argument('format', new VariableReference('format', $formatVariable, new Location(3, 33)), new Location(3, 25))
    //         //                                 ], new Location(3, 19))
    //         //                             ], new Location(3, 13)),
    //         //                         ],
    //         //                         [],
    //         //                         new Location(2, 9)
    //         //                     ),
    //         //                 ],
    //         //                 new Location(1, 11)
    //         //             )
    //         //         ]
    //         //     ),
    //         // ],
    //         // // Repeatable directives
    //         // [
    //         //     <<<GRAPHQL
    //         //         query GetUsersName(\$format: String!) {
    //         //             users {
    //         //                 name
    //         //                     @style(format: \$format)
    //         //                     @someOtherDirective
    //         //                     @style(format: \$format)
    //         //                     @someOtherDirective
    //         //             }
    //         //         }
    //         //     GRAPHQL,
    //         //     new Document(
    //         //         [
    //         //             new QueryOperation(
    //         //                 'GetUsersName',
    //         //                 [
    //         //                     $formatVariable
    //         //                 ],
    //         //                 [],
    //         //                 [
    //         //                     new RelationalField(
    //         //                         'users',
    //         //                         null,
    //         //                         [],
    //         //                         [
    //         //                             new LeafField('name', null, [], [
    //         //                                 new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable, new Location(4, 32)), new Location(4, 24))], new Location(4, 18)),
    //         //                                 new Directive('someOtherDirective', [], new Location(5, 18)),
    //         //                                 new Directive('style', [new Argument('format', new VariableReference('format', $formatVariable, new Location(6, 32)), new Location(6, 24))], new Location(6, 18)),
    //         //                                 new Directive('someOtherDirective', [], new Location(7, 18)),
    //         //                             ], new Location(3, 13)),
    //         //                         ],
    //         //                         [],
    //         //                         new Location(2, 9)
    //         //                     ),
    //         //                 ],
    //         //                 new Location(1, 11)
    //         //             )
    //         //         ]
    //         //     ),
    //         // ],
    //         // // Directive in fragment
    //         // [
    //         //     <<<GRAPHQL
    //         //         query GetUsersName {
    //         //             users {
    //         //                 ...UserProps
    //         //             }
    //         //         }

    //         //         fragment UserProps on User {
    //         //             id
    //         //             posts @someOperationDirective {
    //         //                 id
    //         //             }
    //         //         }
    //         //     GRAPHQL,
    //         //     new Document(
    //         //         [
    //         //             new QueryOperation(
    //         //                 'GetUsersName',
    //         //                 [],
    //         //                 [],
    //         //                 [
    //         //                     new RelationalField(
    //         //                         'users',
    //         //                         null,
    //         //                         [],
    //         //                         [
    //         //                             new FragmentReference('UserProps', new Location(3, 16)),
    //         //                         ],
    //         //                         [],
    //         //                         new Location(2, 9)
    //         //                     ),
    //         //                 ],
    //         //                 new Location(1, 11)
    //         //             )
    //         //         ],
    //         //         [
    //         //             new Fragment('UserProps', 'User', [], [
    //         //                 new LeafField('id', null, [], [], new Location(8, 9)),
    //         //                 new RelationalField('posts', null, [], [
    //         //                     new LeafField('id', null, [], [], new Location(10, 13)),
    //         //                 ], [
    //         //                     new Directive('someOperationDirective', [], new Location(9, 16))
    //         //                 ], new Location(9, 9)),
    //         //             ], new Location(7, 14)),
    //         //         ]
    //         //     ),
    //         // ],
    //         // // Directive in inline fragment
    //         // [
    //         //     <<<GRAPHQL
    //         //         query GetUsersName {
    //         //             users {
    //         //                 ... on User @outside {
    //         //                     id
    //         //                     posts @inside {
    //         //                         id
    //         //                     }
    //         //                 }
    //         //             }
    //         //         }
    //         //     GRAPHQL,
    //         //     new Document(
    //         //         [
    //         //             new QueryOperation(
    //         //                 'GetUsersName',
    //         //                 [],
    //         //                 [],
    //         //                 [
    //         //                     new RelationalField(
    //         //                         'users',
    //         //                         null,
    //         //                         [],
    //         //                         [
    //         //                             new InlineFragment(
    //         //                                 'User',
    //         //                                 [
    //         //                                     new LeafField('id', null, [], [], new Location(4, 17)),
    //         //                                     new RelationalField('posts', null, [], [
    //         //                                         new LeafField('id', null, [], [], new Location(6, 21)),
    //         //                                         ], [
    //         //                                         new Directive('inside', [], new Location(5, 24))
    //         //                                     ], new Location(5, 17))
    //         //                                 ],
    //         //                                 [
    //         //                                     new Directive('outside', [], new Location(3, 26))
    //         //                                 ],
    //         //                                 new Location(3, 20)
    //         //                             )
    //         //                         ],
    //         //                         [],
    //         //                         new Location(2, 9)
    //         //                     ),
    //         //                 ],
    //         //                 new Location(1, 11)
    //         //             )
    //         //         ]
    //         //     ),
    //         // ],
    //     ];
    // }
}
