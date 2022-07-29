<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

interface QueryASTTransformationServiceInterface
{
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
     *         name @export(as: "_name")
     *       }
     *     }
     *
     *     query Two {
     *       firstEcho: echo(value: $_name) @upperCase @export(as: "_ucName")
     *     }
     *
     *     query Three {
     *       secondEcho: echo(value: $_ucName)
     *     }
     *
     * `firstEcho` is normally resolved on the first iteration for `Root`,
     * that is before `name @export(as: "_name")` is resolved on the
     * second iteration on `User`.
     *
     * For that reason, the fields are wrapped in `self`, and the query
     * above is converted to:
     *
     *     query One {
     *       user(by: {id: 1}) {
     *         name @export(as: "_name")
     *       }
     *     }
     *
     *     query Two {
     *       self {
     *         firstEcho: echo(value: $_name) @upperCase @export(as: "_ucName")
     *       }
     *     }
     *
     *     query Three {
     *       self {
     *         self {
     *           secondEcho: echo(value: $_ucName)
     *         }
     *       }
     *     }
     *
     * Now, `firstEcho` is resolved on the third iteration (second on `Root`),
     * which is after `name @export(as: "_name")`.
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
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(array $operations): SplObjectStorage;
}
