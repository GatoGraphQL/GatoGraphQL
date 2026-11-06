<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

/**
 * A type resolver that memoizes results under the AST nodes (fields,
 * directives) of the GraphQL document being executed.
 *
 * Those nodes belong to a single execution, but the type resolver is a
 * service that outlives it. Kept in an SplObjectStorage, every node
 * stays alive for as long as the service does, together with the AST
 * hanging from it. A single request executing many GraphQL queries (as
 * an internal GraphQL server does, running a persisted query per batch
 * of entities) then accumulates every document it ever executed, until
 * it runs out of memory.
 *
 * A WeakMap does not help where the cached value references its own key
 * (eg: a directive resolver holding its directive), as PHP never frees
 * such an entry. Hence the caches are dropped once nothing is executing
 * anymore.
 */
interface ASTNodeCachingTypeResolverInterface
{
    /**
     * Drop the entries cached under AST nodes. To be called only when
     * no GraphQL query is being executed, as the nodes of a query in
     * progress rely on getting the same cached instances back.
     */
    public function resetASTNodeCaches(): void;
}
