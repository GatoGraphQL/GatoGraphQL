<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\ComponentModel\ExtendedSpec\Parser\Ast\Document;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\ComponentModel\AbstractTestCase;
use PoP\Root\Module\ModuleInterface;
use SplObjectStorage;

abstract class AbstractMultipleQueryExecutionQueryASTTransformationServiceTest extends AbstractTestCase
{
    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[\PoP\GraphQLParser\Module::class][\PoP\GraphQLParser\Environment::ENABLE_MULTIPLE_QUERY_EXECUTION] = static::enabled();
        return $moduleClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    protected function getQueryASTTransformationService(): QueryASTTransformationServiceInterface
    {
        /** @var QueryASTTransformationServiceInterface */
        return $this->getService(QueryASTTransformationServiceInterface::class);
    }

    public function testPrepareOperationFieldAndFragmentBondsForMultipleQueryExecution(): void
    {
        /**
         * This is the AST for this GraphQL query:
         *
         *   ```
         *   query One {
         *     film(id: 1) {
         *       title
         *     }
         *   }
         *
         *   query Two {
         *     post(id: 2) {
         *       title
         *     }
         *   }
         *
         *   query Three {
         *     id
         *     ...on QueryRoot @outside {
         *       __typename
         *     }
         *     users {
         *       name
         *       surname
         *     }
         *   }
         *   ```
         *
         * @see layers/API/packages/api/tests/QueryResolution/AbstractMultipleQueryExecutionTest.php Query based on example test there (and then completed a bit more)
         */
        $argument1 = new Argument('id', new Literal(1, new Location(3, 26)), new Location(3, 22));
        $leafField1 = new LeafField('title', null, [], [], new Location(4, 21));
        $relationalField1 = new RelationalField(
            'film',
            null,
            [
                $argument1,
            ],
            [
                $leafField1,
            ],
            [],
            new Location(3, 17)
        );
        $queryOneOperation = new QueryOperation(
            'One',
            [],
            [],
            [
                $relationalField1
            ],
            new Location(2, 19)
        );

        $argument2 = new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22));
        $leafField2 = new LeafField('title', null, [], [], new Location(10, 21));
        $relationalField2 = new RelationalField(
            'post',
            null,
            [
                $argument2,
            ],
            [
                $leafField2,
            ],
            [],
            new Location(9, 17)
        );
        $queryTwoOperation = new QueryOperation(
            'Two',
            [],
            [],
            [
                $relationalField2
            ],
            new Location(8, 19)
        );

        $leafField31 = new LeafField('id', null, [], [], new Location(15, 17));
        $leafField32 = new LeafField('__typename', null, [], [], new Location(17, 19));
        $inlineFragment3 = new InlineFragment(
            'QueryRoot',
            [
                $leafField32
            ],
            [
                new Directive('outside', [], new Location(16, 33))
            ],
            new Location(16, 17)
        );
        $leafField33 = new LeafField('name', null, [], [], new Location(20, 19));
        $leafField34 = new LeafField('surname', null, [], [], new Location(21, 19));
        $relationalField3 = new RelationalField(
            'users',
            null,
            [],
            [
                $leafField33,
                $leafField34,
            ],
            [],
            new Location(19, 17)
        );
        $queryThreeOperation = new QueryOperation(
            'Three',
            [],
            [],
            [
                $leafField31,
                $inlineFragment3,
                $relationalField3
            ],
            new Location(8, 19)
        );

        $operations = [
            $queryOneOperation,
            $queryTwoOperation,
            $queryThreeOperation,
        ];

        /** @var SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>> */
        $expectedOperationFieldAndFragmentBonds = new SplObjectStorage();
        $expectedOperationFieldAndFragmentBonds[$queryOneOperation] = [
            $relationalField1
        ];

        if (!static::enabled()) {
            $expectedOperationFieldAndFragmentBonds[$queryTwoOperation] = [
                $relationalField2,
            ];
            $expectedOperationFieldAndFragmentBonds[$queryThreeOperation] = [
                $leafField31,
                $inlineFragment3,
                $relationalField3,
            ];
        } else {
            $expectedOperationFieldAndFragmentBonds[$queryTwoOperation] = [
                new RelationalField(
                    'self',
                    '_dynamicSelf_op1_level1_',
                    [],
                    [
                        new RelationalField(
                            'self',
                            '_dynamicSelf_op1_level2_',
                            [],
                            [
                                $relationalField2,
                            ],
                            [],
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                    ],
                    [],
                    ASTNodesFactory::getNonSpecificLocation()
                )
            ];
            $expectedOperationFieldAndFragmentBonds[$queryThreeOperation] = [
                new RelationalField(
                    'self',
                    '_dynamicSelf_op2_level1_',
                    [],
                    [
                        new RelationalField(
                            'self',
                            '_dynamicSelf_op2_level2_',
                            [],
                            [
                                new RelationalField(
                                    'self',
                                    '_dynamicSelf_op2_level3_',
                                    [],
                                    [
                                        new RelationalField(
                                            'self',
                                            '_dynamicSelf_op2_level4_',
                                            [],
                                            [
                                                $leafField31,
                                                $inlineFragment3,
                                                $relationalField3,
                                            ],
                                            [],
                                            ASTNodesFactory::getNonSpecificLocation()
                                        ),
                                    ],
                                    [],
                                    ASTNodesFactory::getNonSpecificLocation()
                                ),
                            ],
                            [],
                            ASTNodesFactory::getNonSpecificLocation()
                        ),
                    ],
                    [],
                    ASTNodesFactory::getNonSpecificLocation()
                )
            ];
        }

        $document = new Document($operations);
        $operationFieldAndFragmentBonds = $this->getQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution($document, $operations, []);

        /**
         * Doing `assertEquals` on SplObjectStorage doesn't work!
         * So compare the multiple elements 1 by 1.
         */
        $this->assertEquals(
            $expectedOperationFieldAndFragmentBonds->count(),
            $operationFieldAndFragmentBonds->count()
        );
        /** @var OperationInterface $operation */
        foreach ($expectedOperationFieldAndFragmentBonds as $operation) {
            $expectedFieldAndFragmentBonds = $expectedOperationFieldAndFragmentBonds[$operation];
            $this->assertContains(
                $operation,
                $operationFieldAndFragmentBonds
            );
            $this->assertEquals(
                $expectedFieldAndFragmentBonds,
                $operationFieldAndFragmentBonds[$operation]
            );
        }
    }
}
