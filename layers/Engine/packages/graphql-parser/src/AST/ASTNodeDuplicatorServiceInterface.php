<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;

interface ASTNodeDuplicatorServiceInterface
{
    /**
     * @param Fragment[] $fragments
     */
    public function getExclusiveFragment(
        FragmentReference $fragmentReference,
        array $fragments,
    ): ?Fragment;
}
