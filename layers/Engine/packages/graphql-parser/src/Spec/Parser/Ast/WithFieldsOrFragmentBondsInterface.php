<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithFieldsOrFragmentBondsInterface
{
    /**
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldsOrFragmentBonds(): array;
}
