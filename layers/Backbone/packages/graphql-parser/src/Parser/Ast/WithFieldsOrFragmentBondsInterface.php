<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

interface WithFieldsOrFragmentBondsInterface
{
    /**
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldsOrFragmentBonds(): array;
}
