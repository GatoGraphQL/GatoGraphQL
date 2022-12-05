<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;

class ASTNodeDuplicatorService implements ASTNodeDuplicatorServiceInterface
{
    /**
     * @param Fragment[] $fragments
     */
    public function getFragment(
        FragmentReference $fragmentReference,
        array $fragments,
    ): ?Fragment {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentReference->getName()) {
                return $fragment;
            }
        }
        return null;
    }
}
