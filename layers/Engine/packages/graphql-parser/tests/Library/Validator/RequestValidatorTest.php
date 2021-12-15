<?php

/**
 * Date: 27.10.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Library\Validator;

use GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use GraphQLByPoP\GraphQLParser\Execution\Request;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Argument;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Field;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Fragment;
use GraphQLByPoP\GraphQLParser\Parser\Ast\FragmentReference;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Query;
use GraphQLByPoP\GraphQLParser\Parser\Location;
use GraphQLByPoP\GraphQLParser\Validator\RequestValidator\RequestValidator;
use PHPUnit\Framework\TestCase;

class RequestValidatorTest extends TestCase
{

    /**
     * @dataProvider invalidRequestProvider
     */
    public function testInvalidRequests(Request $request)
    {
        $this->expectException(InvalidRequestException::class);
        (new RequestValidator())->validate($request);
    }

    public function invalidRequestProvider()
    {
        $variable1 = (new Variable('test', 'Int', false, false, true, new Location(1, 1)))->setUsed(true);
        $variable2 = (new Variable('test2', 'Int', false, false, true, new Location(1, 1)))->setUsed(true);
        $variable3 = (new Variable('test3', 'Int', false, false, true, new Location(1, 1)))->setUsed(false);

        return [
            [
                new Request([
                    'queries'            => [
                        new Query('test', null, [], [
                            new FragmentReference('reference', new Location(1, 1))
                        ], [], new Location(1, 1))
                    ],
                    'fragmentReferences' => [
                        new FragmentReference('reference', new Location(1, 1))
                    ]
                ])
            ],
            [
                new Request([
                    'queries'            => [
                        new Query('test', null, [], [
                            new FragmentReference('reference', new Location(1, 1)),
                            new FragmentReference('reference2', new Location(1, 1)),
                        ], [], new Location(1, 1))
                    ],
                    'fragments'          => [
                        new Fragment('reference', 'TestType', [], [], new Location(1, 1))
                    ],
                    'fragmentReferences' => [
                        new FragmentReference('reference', new Location(1, 1)),
                        new FragmentReference('reference2', new Location(1, 1))
                    ]
                ])
            ],
            [
                new Request([
                    'queries'            => [
                        new Query('test', null, [], [
                            new FragmentReference('reference', new Location(1, 1)),
                        ], [], new Location(1, 1))
                    ],
                    'fragments'          => [
                        new Fragment('reference', 'TestType', [], [], new Location(1, 1)),
                        new Fragment('reference2', 'TestType', [], [], new Location(1, 1))
                    ],
                    'fragmentReferences' => [
                        new FragmentReference('reference', new Location(1, 1))
                    ]
                ])
            ],
            [
                new Request([
                    'queries'            => [
                        new Query(
                            'test',
                            null,
                            [
                                new Argument('test', new VariableReference('test', null, new Location(1, 1)), new Location(1, 1))
                            ],
                            [
                                new Field('test', null, [], [], new Location(1, 1))
                            ],
                            [],
                            new Location(1, 1)
                        )
                    ],
                    'variableReferences' => [
                        new VariableReference('test', null, new Location(1, 1))
                    ]
                ], ['test' => 1])
            ],
            [
                new Request([
                    'queries'            => [
                        new Query('test', null, [
                            new Argument('test', new VariableReference('test', $variable1, new Location(1, 1)), new Location(1, 1)),
                            new Argument('test2', new VariableReference('test2', $variable2, new Location(1, 1)), new Location(1, 1)),
                        ], [
                            new Field('test', null, [], [], new Location(1, 1))
                        ], [], new Location(1, 1))
                    ],
                    'variables'          => [
                        $variable1,
                        $variable2,
                        $variable3
                    ],
                    'variableReferences' => [
                        new VariableReference('test', $variable1, new Location(1, 1)),
                        new VariableReference('test2', $variable2, new Location(1, 1))
                    ]
                ], ['test' => 1, 'test2' => 2])
            ]
        ];
    }
}
