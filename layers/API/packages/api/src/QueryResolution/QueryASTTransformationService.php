<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use function max;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
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
     * @var array<string,array<string,RelationalField>>
     */
    private array $fieldInstanceContainer = [];

    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
        array $operations,
        array $fragments,
    ): SplObjectStorage {
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
         * The cache must be stored per query, or otherwise
         * executing multiple PHPUnit tests may access the
         * same cached objects and produce errors.
         */
        $reconstructedDocument = implode(
            PHP_EOL,
            array_merge(
                array_map(
                    fn (OperationInterface $operation) => $operation->asQueryString(),
                    $operations
                ),
                array_map(
                    fn (Fragment $fragment) => $fragment->asQueryString(),
                    $fragments
                )
            )
        );
        $documentHash = hash('md5', $reconstructedDocument);

        /**
         * Wrap subsequent queries "field and fragment bonds" under
         * the required multiple levels of `self`.
         *
         * Because it starts in `0`, the first operation will not
         * receive any `self`
         */
        $accumulatedMaximumFieldDepth = 0;
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
            for ($level = 0; $level < $accumulatedMaximumFieldDepth; $level++) {
                /**
                 * Use an alias to both help visualize which is the field (optional),
                 * and get its cached instance (mandatory!)
                 */
                $alias = sprintf(
                    '_%s_op%s_level%s_',
                    'dynamicSelf',
                    $operationOrder,
                    $accumulatedMaximumFieldDepth - $level
                );
                if (!isset($this->fieldInstanceContainer[$documentHash][$alias])) {
                    $this->fieldInstanceContainer[$documentHash][$alias] = new RelationalField(
                        'self',
                        $alias,
                        [],
                        $fieldOrFragmentBonds,
                        [],
                        LocationHelper::getNonSpecificLocation()
                    );
                }
                $fieldOrFragmentBonds = [
                    $this->fieldInstanceContainer[$documentHash][$alias],
                ];
            }
            $operationFieldOrFragmentBonds[$operation] = $fieldOrFragmentBonds;

            /**
             * Add the maximum depth of this operation to the counter
             */
            $accumulatedMaximumFieldDepth += $this->getOperationMaximumFieldDepth($operation, $fragments);
        }
        return $operationFieldOrFragmentBonds;
    }

    /**
     * @param Fragment[] $fragments
     */
    public function getOperationMaximumFieldDepth(OperationInterface $operation, array $fragments): int
    {
        /**
         * Also handle the boundaries: an empty operation
         */
        $fieldsOrFragmentBonds = $operation->getFieldsOrFragmentBonds();
        if ($fieldsOrFragmentBonds === []) {
            return 0;
        }

        $depths = array_map(
            fn (FieldInterface|FragmentBondInterface $fieldOrFragmentBond) => $this->getFieldOrFragmentBondDepth(1, $fieldOrFragmentBond, $fragments),
            $fieldsOrFragmentBonds
        );
        return max($depths);
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFieldOrFragmentBondDepth(int $accumulator, FieldInterface|FragmentBondInterface $fieldOrFragmentBond, array $fragments): int
    {
        if ($fieldOrFragmentBond instanceof LeafField) {
            // Reached last level
            return $accumulator;
        }

        if ($fieldOrFragmentBond instanceof RelationalField) {
            /** @var RelationalField */
            $relationalField = $fieldOrFragmentBond;

            /**
             * Also handle the boundaries: an empty relational field
             */
            $fieldsOrFragmentBonds = $relationalField->getFieldsOrFragmentBonds();
            if ($fieldsOrFragmentBonds === []) {
                return $accumulator;
            }

            $depths = array_map(
                fn (FieldInterface|FragmentBondInterface $fieldOrFragmentBond) => $this->getFieldOrFragmentBondDepth(1 + $accumulator, $fieldOrFragmentBond, $fragments),
                $fieldsOrFragmentBonds
            );
            return max($depths);
        }

        /**
         * Both Fragment References and Inline Fragments also do +1
         * because the engine will add an extra conditional module
         * to calculate these (with alias "_..._isTypeOrImplementsAll_..._").
         *
         * @see layers/API/packages/api/src/ComponentProcessors/AbstractRelationalFieldQueryDataComponentProcessor.php
         */
        if ($fieldOrFragmentBond instanceof InlineFragment) {
            /** @var InlineFragment */
            $inlineFragment = $fieldOrFragmentBond;

            /**
             * Also handle the boundaries: an empty inline fragment
             */
            $fieldsOrFragmentBonds = $inlineFragment->getFieldsOrFragmentBonds();
            if ($fieldsOrFragmentBonds === []) {
                return $accumulator;
            }

            $depths = array_map(
                fn (FieldInterface|FragmentBondInterface $fieldOrFragmentBond) => $this->getFieldOrFragmentBondDepth(1 + $accumulator, $fieldOrFragmentBond, $fragments),
                $fieldsOrFragmentBonds
            );
            return max($depths);
        }

        /** @var FragmentReference */
        $fragmentReference = $fieldOrFragmentBond;
        $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
        if ($fragment === null) {
            return $accumulator;
        }

        /**
         * Also handle the boundaries: an empty fragment
         */
        $fieldsOrFragmentBonds = $fragment->getFieldsOrFragmentBonds();
        if ($fieldsOrFragmentBonds === []) {
            return $accumulator;
        }

        $depths = array_map(
            fn (FieldInterface|FragmentBondInterface $fieldOrFragmentBond) => $this->getFieldOrFragmentBondDepth(1 + $accumulator, $fieldOrFragmentBond, $fragments),
            $fieldsOrFragmentBonds
        );
        return max($depths);
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(string $name, array $fragments): ?Fragment
    {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $name) {
                return $fragment;
            }
        }

        return null;
    }
}
