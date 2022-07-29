<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use SplObjectStorage;

class QueryASTTransformationService implements QueryASTTransformationServiceInterface
{
    /**
     * @param OperationInterface[] $operations
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(array $operations): SplObjectStorage
    {
        $operationsCount = count($operations);
        for ($operationOrder = 0; $operationOrder < $operationsCount; $operationOrder++) {
            $operation = $operations[$operationOrder];
            $fieldOrFragmentBonds = $operation->getFieldsOrFragmentBonds();
            for ($i = 0; $i < $operationOrder; $i++) {
                /**
                 * Use an alias to both help visualize which is the field (optional),
                 * and get its cached instance (mandatory!)
                 */
                $alias = sprintf(
                    '_%s_op%s_level%s_',
                    'dynamicSelf',
                    $operationOrder,
                    $i
                );
                if (!isset($this->fieldInstanceContainer[$alias])) {
                    $this->fieldInstanceContainer[$alias] = new RelationalField(
                        'self',
                        $alias,
                        [],
                        $fieldOrFragmentBonds,
                        [],
                        LocationHelper::getNonSpecificLocation()
                    );
                }
                $fieldOrFragmentBonds = [
                    $this->fieldInstanceContainer[$alias],
                ];
            }
        }
        return $operations;
    }
}
