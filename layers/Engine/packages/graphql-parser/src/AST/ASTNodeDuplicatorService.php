<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use SplObjectStorage;

class ASTNodeDuplicatorService implements ASTNodeDuplicatorServiceInterface
{
    /**
     * @var SplObjectStorage<FragmentReference,Fragment>
     */
    protected SplObjectStorage $fragmentReferenceFragments;

    /**
     * Retrieve a specific and dynamic Fragment for that FragmentReference
     *
     * @param Fragment[] $fragments
     */
    public function getFragment(
        FragmentReference $fragmentReference,
        array $fragments,
    ): ?Fragment {
        if ($this->fragmentReferenceFragments->contains($fragmentReference)) {
            return $this->fragmentReferenceFragments[$fragmentReference];
        }
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentReference->getName()) {
                $this->fragmentReferenceFragments[$fragmentReference] = $fragment;
                return $fragment;
            }
        }
        return null;
    }
}
