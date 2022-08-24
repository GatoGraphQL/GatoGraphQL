<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;

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
    public function getFragment(
        string $fragmentName,
        array $fragments,
    ): ?Fragment;
}
