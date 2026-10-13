<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;

interface ASTHelperServiceInterface
{
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    public function getAllFieldsFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array;

    /**
     * @param Fragment[] $fragments
     */
    public function isFieldEquivalentToField(
        FieldInterface $thisField,
        FieldInterface $oppositeField,
        array $fragments
    ): bool;

    /**
     * Indicate if the fragment spread or inline fragment must be
     * included, as decided by its `@include` and `@skip` directives
     */
    public function isFragmentBondIncluded(FragmentReference|InlineFragment $fragmentBond): bool;
}
