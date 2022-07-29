<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\ParserInterface;
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
        $queryOneOperation = new QueryOperation(
            'One',
            [],
            [],
            [
                new RelationalField(
                    'film',
                    null,
                    [
                        new Argument('id', new Literal(1, new Location(3, 26)), new Location(3, 22)),
                    ],
                    [
                        new LeafField('title', null, [], [], new Location(4, 21)),
                    ],
                    [],
                    new Location(3, 17)
                )
            ],
            new Location(2, 19)
        );
        $queryTwoOperation = new QueryOperation(
            'Two',
            [],
            [],
            [
                new RelationalField(
                    'post',
                    null,
                    [
                        new Argument('id', new Literal(2, new Location(9, 26)), new Location(9, 22)),
                    ],
                    [
                        new LeafField('title', null, [], [], new Location(10, 21)),
                    ],
                    [],
                    new Location(9, 17)
                )
            ],
            new Location(8, 19)
        );
        $operations = [
            $queryOneOperation,
            $queryTwoOperation,
        ];

        $queryASTTransformationService = $this->getQueryASTTransformationService();
        $operationFieldAndFragmentBonds = $queryASTTransformationService->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution($operations);

        $this->assertEquals(new SplObjectStorage(), $operationFieldAndFragmentBonds);
    }
}
