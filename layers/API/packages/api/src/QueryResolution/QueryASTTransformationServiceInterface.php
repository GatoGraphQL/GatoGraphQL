<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

interface QueryASTTransformationServiceInterface
{
    /**
     * The AST for execution can be different than the
     * parsed AST from the GraphQL document:
     *
     * - Wrap fields in "self" for Multiple Query Execution
     * - Add SuperRoot fields for GraphQL
     *
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage;

    /**
     * Multiple Query Execution: In order to have the fields
     * of the subsequent operations be resolved in the same
     * order as the operations (which is necessary for `@export`
     * to work), then wrap them on a "self" field.
     *
     * For instance, in the following GraphQL query:
     *
     *     query One {
     *       user(by: {id: 1}) {
     *         name @export(as: "name")
     *       }
     *     }
     *
     *     query Two {
     *       firstEcho: _echo(value: $name) @upperCase @export(as: "ucName")
     *     }
     *
     *     query Three {
     *       secondEcho: _echo(value: $ucName)
     *     }
     *
     * `firstEcho` is normally resolved on the first iteration for `Root`,
     * that is before `name @export(as: "name")` is resolved on the
     * second iteration on `User`.
     *
     * For that reason, the fields are wrapped in `self`, and the query
     * above is converted to:
     *
     *     query One {
     *       user(by: {id: 1}) {
     *         name @export(as: "name")
     *       }
     *     }
     *
     *     query Two {
     *       self {
     *         self {
     *           firstEcho: _echo(value: $name) @upperCase @export(as: "ucName")
     *         }
     *       }
     *     }
     *
     *     query Three {
     *       self {
     *         self {
     *           self {
     *             secondEcho: _echo(value: $ucName)
     *           }
     *         }
     *       }
     *     }
     *
     * Now, `firstEcho` is resolved on the third iteration (second on `Root`),
     * which is after `name @export(as: "name")`.
     *
     * --------------------------------------------------------------------
     *
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
     *
     * --------------------------------------------------------------------
     *
     * If Multiple Query Execution is disabled, then there's no need
     * to wrap the fields under "self".
     *
     * As a result, fields from different queries will be resolved
     * all together.
     *
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage;

    /**
     * Calculate the maximum field depth in an operation.
     *
     * Eg: this query has maximum field depth 4:
     *
     * ```
     * {
     *   level1 {
     *     level2 {
     *       level3 {
     *         level4 # <= maximum depth field
     *       }
     *     }
     *   }
     * }
     * ```
     *
     * @param Fragment[] $fragments
     */
    public function getOperationMaximumFieldDepth(OperationInterface $operation, array $fragments): int;
}
