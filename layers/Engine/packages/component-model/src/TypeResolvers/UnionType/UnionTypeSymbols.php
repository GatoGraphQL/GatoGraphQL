<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

class UnionTypeSymbols
{
    /**
     * Watch out! This can't be the same as SchemaDefinitionTokens::NAMESPACE_SEPARATOR!
     *
     * Don't use "/" because it's the separator for block names (e.g. "core/paragraph"),
     * which is used as the ID of BlockType instances.
     */
    public final const OBJECT_COMPOSED_TYPE_ID_SEPARATOR = '::';
}
