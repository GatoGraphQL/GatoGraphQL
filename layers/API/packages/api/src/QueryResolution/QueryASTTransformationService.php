<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\ComponentModel\App;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\AST\ASTNodeDuplicatorServiceInterface;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

use function max;

class QueryASTTransformationService implements QueryASTTransformationServiceInterface
{
    use BasicServiceTrait;

    /**
     * Because fields are stored in SplObjectStorage,
     * the same instance must be retrieved in every case.
     * Then, cache and reuse every created field
     *
     * @var SplObjectStorage<Document,array<string,RelationalField>>
     */
    private SplObjectStorage $fieldInstanceContainer;

    private ?ASTNodeDuplicatorServiceInterface $astNodeDuplicatorService = null;

    final public function setASTNodeDuplicatorService(ASTNodeDuplicatorServiceInterface $astNodeDuplicatorService): void
    {
        $this->astNodeDuplicatorService = $astNodeDuplicatorService;
    }
    final protected function getASTNodeDuplicatorService(): ASTNodeDuplicatorServiceInterface
    {
        /** @var ASTNodeDuplicatorServiceInterface */
        return $this->astNodeDuplicatorService ??= $this->instanceManager->getInstance(ASTNodeDuplicatorServiceInterface::class);
    }

    public function __construct()
    {
        /**
         * @var SplObjectStorage<Document,array<string,RelationalField>>
         */
        $fieldInstanceContainer = new SplObjectStorage();
        $this->fieldInstanceContainer = $fieldInstanceContainer;
    }

    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage {
        return $this->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
            $document,
            $operations,
            $fragments,
        );
    }

    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
        Document $document,
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
                /**
                 * Allow to override the original fields from the operation,
                 * to inject the SuperRoot field for GraphQL
                 */
                $operationFieldOrFragmentBonds[$operation] = $this->getOperationFieldsOrFragmentBonds($document, $operation);
            }
            return $operationFieldOrFragmentBonds;
        }

        $nonSpecificLocation = ASTNodesFactory::getNonSpecificLocation();

        /**
         * The cache must be stored per Document, or otherwise
         * executing multiple PHPUnit tests may access the
         * same cached objects and produce errors.
         *
         * @var array<string,RelationalField>
         */
        $documentFieldInstanceContainer = $this->fieldInstanceContainer[$document] ?? [];

        /**
         * Wrap subsequent queries "field and fragment bonds" under
         * the required multiple levels of `self`.
         *
         * Because it starts in `0`, the first operation will not
         * receive any `self`
         */
        $accumulatedMaximumFieldDepth = 0;
        /**
         * Allow the SuperRoot to add extra fields, and these must
         * also be considered when generating the number of "self"
         * fields.
         */
        $operationInitialDepth = $this->getOperationInitialDepth();
        for ($operationOrder = 0; $operationOrder < $operationsCount; $operationOrder++) {
            $operation = $operations[$operationOrder];
            /**
             * Allow to override the original fields from the operation,
             * to inject the SuperRoot field for GraphQL
             */
            $fieldOrFragmentBonds = $this->getOperationFieldsOrFragmentBonds($document, $operation);

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
            for ($level = $accumulatedMaximumFieldDepth; $level > 0; $level--) {
                /**
                 * Use an alias to both help visualize which is the field (optional),
                 * and get its cached instance (mandatory!)
                 */
                $alias = sprintf(
                    '_%s_op%s_level%s_',
                    'dynamicSelf',
                    $operationOrder,
                    $level
                );
                if (!isset($documentFieldInstanceContainer[$alias])) {
                    $documentFieldInstanceContainer[$alias] = new RelationalField(
                        'self',
                        $alias,
                        [],
                        $fieldOrFragmentBonds,
                        [],
                        $nonSpecificLocation
                    );
                }
                $fieldOrFragmentBonds = [
                    $documentFieldInstanceContainer[$alias],
                ];
            }
            $operationFieldOrFragmentBonds[$operation] = $fieldOrFragmentBonds;

            /**
             * Add the maximum depth of this operation to the counter
             */
            $accumulatedMaximumFieldDepth += $operationInitialDepth + $this->getOperationMaximumFieldDepth($operation, $fragments);
        }
        $this->fieldInstanceContainer[$document] = $documentFieldInstanceContainer;
        return $operationFieldOrFragmentBonds;
    }

    /**
     * Allow to override the original fields from the operation,
     * to inject the SuperRoot field for GraphQL
     *
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function getOperationFieldsOrFragmentBonds(
        Document $document,
        OperationInterface $operation,
    ): array {
        return $operation->getFieldsOrFragmentBonds();
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
            fn (FieldInterface|FragmentBondInterface $fieldOrFragmentBond) => $this->getFieldOrFragmentBondDepth(
                1,
                $fieldOrFragmentBond,
                $fragments
            ),
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
        $fragment = $this->getASTNodeDuplicatorService()->getExclusiveFragment($fragmentReference, $fragments);
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
     * The initial "cushion" of extra "self" fields to add
     * at the beginning of an operation.
     *
     * It is needed for the SuperRoot to add its required fields,
     * and have these still be executed after all previous fields
     * from the previous operation.
     */
    protected function getOperationInitialDepth(): int
    {
        return 0;
    }
}
