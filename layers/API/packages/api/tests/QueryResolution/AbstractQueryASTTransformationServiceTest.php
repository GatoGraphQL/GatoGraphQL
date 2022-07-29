<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\AbstractTestCase;
use SplObjectStorage;

abstract class AbstractQueryASTTransformationServiceTest extends AbstractTestCase
{
    /**
     * @return array<string, mixed> [key]: Module class, [value]: Configuration
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
        return $this->getService(QueryASTTransformationServiceInterface::class);
    }

    public function testQueryASTTransformationService()
    {
        /**
         * This is the AST for this GraphQL query:
         * 
         *   ```
         *   query One {
         *     film(id: 1) {
         *       title
         *   }
         *
         *   query Two {
         *     post(id: 2) {
         *       title
         *     }
         *   }
         *   ```
         *
         * @see layers/API/packages/api/tests/QueryResolution/AbstractMultipleQueryExecutionTest.php Original query test
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
        $operations = [
            $queryOneOperation,
            $queryTwoOperation,
        ];

        /** @var SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface> */
        $expectedOperationFieldAndFragmentBonds = new SplObjectStorage();
        $expectedOperationFieldAndFragmentBonds[$queryOneOperation] = [
            $relationalField1
        ];

        if (!static::enabled()) {
            $expectedOperationFieldAndFragmentBonds[$queryTwoOperation] = [
                $relationalField2
            ];
        } else {
            $expectedOperationFieldAndFragmentBonds[$queryTwoOperation] = new RelationalField(
                'self',
                '_dynamicSelf_op0_level0_',
                [],
                [
                    $relationalField2
                ],
                [],
                LocationHelper::getNonSpecificLocation()
            );
        }

        $this->assertEquals(
            $expectedOperationFieldAndFragmentBonds,
            $this->getQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution($operations)
        );
    }
}
