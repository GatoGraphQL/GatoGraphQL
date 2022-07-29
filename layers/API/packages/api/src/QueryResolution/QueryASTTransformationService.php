<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use SplObjectStorage;

class QueryASTTransformationService implements QueryASTTransformationServiceInterface
{
    /**
     * Because fields are stored in SplObjectStorage,
     * the same instance must be retrieved in every case.
     * Then, cache and reuse every created field
     *
     * @var array<string,RelationalField>
     */
    private array $fieldInstanceContainer = [];

    /**
     * @param OperationInterface[] $operations
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(array $operations): SplObjectStorage
    {
        /** @var SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>> */
        $operationFieldOrFragmentBonds = new SplObjectStorage();
        $operationsCount = count($operations);
        
        /**
         * If there's only 1 operation, then return its contents directly
         * (no need to calculate anything!).
         *
         * ----------------------------------------------------------------
         *
         * If Multiple Query Execution is disabled, then there's no need
         * to wrap the fields under "self".
         *
         * As a result, fields from different queries will be resolved
         * all together.
         *
         * @var GraphQLParserModuleConfiguration
         */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if ($operationsCount === 1 || !$moduleConfiguration->enableMultipleQueryExecution()) {
            foreach ($operations as $operation) {
                $operationFieldOrFragmentBonds[$operation] = $operation->getFieldsOrFragmentBonds();
            }
            return $operationFieldOrFragmentBonds;
        }

        /**
         * Wrap subsequent queries "field and fragment bonds" under
         * the required multiple levels of `self`
         */
        for ($operationOrder = 0; $operationOrder < $operationsCount; $operationOrder++) {
            $operation = $operations[$operationOrder];
            $fieldOrFragmentBonds = $operation->getFieldsOrFragmentBonds();

            /**
             * Each level needs to add as many "self" as the sum of the
             * maximum field depth in all previous queries, so that
             * the 1st field in the subsequent operation is executed
             * after the deepest field in the previous query:
             *
             *   ```
             *   query One {
             *     field {
             *       field {
             *         field {
             *           field {
             *             firstQueryMaximumDepthField: field
             *           }
             *         }
             *       }
             *     }
             *   }
             *
             *   query Two {
             *     secondQueryField: field # <= Must be resolved after "firstQueryMaximumDepthField"
             *   }
             *
             *   query Three {
             *     field # <= Must be resolved after "secondQueryField"
             *   }
             *   ```
             *
             * This will then become:
             *
             *   ```
             *   query One {
             *     field {
             *       field {
             *         field {
             *           field {
             *             firstQueryMaximumDepthField: field
             *           }
             *         }
             *       }
             *     }
             *   }
             *
             *   query Two {
             *     self {
             *       self {
             *         self {
             *           self {
             *             self {
             *               secondQueryField: field # <= Must be resolved after "firstQueryMaximumDepthField"
             *             }
             *           }
             *         }
             *       }
             *     }
             *   }
             *
             *   query Three {
             *     self {
             *       self {
             *         self {
             *           self {
             *             self {
             *               self {
             *                 field # <= Must be resolved after "secondQueryField"
             *               }
             *             }
             *           }
             *         }
             *       }
             *     }
             *   }
             *   ```
             */
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
            $operationFieldOrFragmentBonds[$operation] = $fieldOrFragmentBonds;
        }
        return $operationFieldOrFragmentBonds;
    }
}
