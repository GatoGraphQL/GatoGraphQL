<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithFieldsOrFragmentBondsInterface
{
    /**
     * @return array<FieldInterface|FragmentBondInterface>
     */
    public function getFieldsOrFragmentBonds(): array;
}
