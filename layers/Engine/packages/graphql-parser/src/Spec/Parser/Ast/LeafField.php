<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

class LeafField extends AbstractField
{
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     *
     * Watch out: `{ title: title }` is equivalent to `{ title }`
     *
     * @see https://spec.graphql.org/draft/#sec-Field-Selection-Merging
     */
    public function isEquivalentTo(LeafField $leafField): bool
    {
        return $this->doIsEquivalentTo($leafField);
    }
}
