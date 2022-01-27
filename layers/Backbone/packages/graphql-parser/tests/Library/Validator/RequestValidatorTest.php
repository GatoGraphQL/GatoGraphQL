<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Library\Validator;

use PHPUnit\Framework\TestCase;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocument;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\QueryOperation;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField;
use PoPBackbone\GraphQLParser\Parser\Location;

class RequestValidatorTest extends TestCase
{
    /**
     * @dataProvider invalidRequestProvider
     */
    public function testInvalidRequests(ExecutableDocument $executableDocument)
    {
        $this->expectException(InvalidRequestException::class);
        $executableDocument->validateAndInitialize();
    }

    public function invalidRequestProvider()
    {
        $context = new Context(null, [
            'test' => 'ponga',
            'test2' => 'chonga',
            'test3' => 'conga',
        ]);
        $variable1 = new Variable('test', 'Int', false, false, true, new Location(1, 1));
        $variable1->setContext($context);
        $variable2 = new Variable('test2', 'Int', false, false, true, new Location(1, 1));
        $variable2->setContext($context);
        $variable3 = new Variable('test3', 'Int', false, false, true, new Location(1, 1));
        $variable3->setContext($context);

        return [
            [
                (new ExecutableDocument(
                    new Document([
                        new QueryOperation(
                            'saranga',
                            [],
                            [],
                            [
                                new RelationalField('test', null, [], [
                                    new FragmentReference('reference', new Location(1, 1))
                                ], [], new Location(1, 1)),
                                new FragmentReference('reference', new Location(1, 1))
                            ],
                            new Location(1, 1)
                        )]),
                    new Context()
                )),
            ],
            [
                (new ExecutableDocument(
                    new Document([
                        new QueryOperation(
                            'saranga',
                            [],
                            [],
                            [
                                new RelationalField('test', null, [], [
                                    new FragmentReference('reference', new Location(1, 1)),
                                    new FragmentReference('reference2', new Location(1, 1)),
                                ], [], new Location(1, 1))
                            ],
                            new Location(1, 1)
                        )
                        ], [
                        new Fragment('reference', 'TestType', [], [], new Location(1, 1))
                    ]),
                    new Context()
                ))
            ],
            [
                (new ExecutableDocument(
                    new Document([
                        new QueryOperation(
                            'saranga',
                            [],
                            [],
                            [
                                new RelationalField('test', null, [], [
                                    new FragmentReference('reference', new Location(1, 1)),
                                ], [], new Location(1, 1))
                            ],
                            new Location(1, 1)
                        )
                        ], [
                            new Fragment('reference', 'TestType', [], [], new Location(1, 1)),
                            new Fragment('reference2', 'TestType', [], [], new Location(1, 1))
                        ]),
                    new Context()
                ))
            ],
            [
                (new ExecutableDocument(
                    new Document([
                        new QueryOperation(
                            'saranga',
                            [],
                            [],
                            [
                                new RelationalField(
                                    'test',
                                    null,
                                    [
                                        new Argument('test', new VariableReference('test', null, new Location(1, 1)), new Location(1, 1))
                                    ],
                                    [
                                        new LeafField('test', null, [], [], new Location(1, 1))
                                    ],
                                    [],
                                    new Location(1, 1)
                                )
                            ],
                            new Location(1, 1)
                        )
                        ]),
                    new Context()
                ))
            ],
            [
                (new ExecutableDocument(
                    new Document([
                        new QueryOperation(
                            'saranga',
                            [],
                            [],
                            [
                                new RelationalField('test', null, [
                                    new Argument('test', new VariableReference('test', $variable1, new Location(1, 1)), new Location(1, 1)),
                                    new Argument('test2', new VariableReference('test2', $variable2, new Location(1, 1)), new Location(1, 1)),
                                ], [
                                    new LeafField('test', null, [], [], new Location(1, 1))
                                ], [], new Location(1, 1))
                            ],
                            new Location(1, 1)
                        )
                        ]),
                    new Context()
                ))
            ]
        ];
    }
}
