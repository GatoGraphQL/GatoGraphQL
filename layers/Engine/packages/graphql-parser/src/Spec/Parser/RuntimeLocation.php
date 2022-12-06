<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;

/**
 * Location for "dynamic" AST nodes, i.e. those
 * not present in the the GraphQL query, but created
 * by the engine for ease of implementation.
 *
 * These AST nodes may be a surrogate for an actual
 * AST node in the query (such as the cloned Fragment
 * nodes, one for each FragmentReference). In this case,
 * keep a reference to this "static" or "upstream" AST node,
 * so the path to them can still be added in the response errors.
 */
class RuntimeLocation extends Location
{
    public function __construct(
        protected ?AstInterface $staticASTNode = null,
        int $line = -1,
        int $column = -1,
    ) {
        parent::__construct(
            $line,
            $column
        );
    }

    public function getStaticASTNode(): ?AstInterface
    {
        return $this->staticASTNode;
    }
}
