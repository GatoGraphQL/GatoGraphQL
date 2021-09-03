<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Union;

class UnionTypeSymbols
{
    /**
     * Watch out! This can't be the same as TOKEN_NAMESPACE_SEPARATOR!
     */
    public const DBOBJECT_COMPOSED_TYPE_ID_SEPARATOR = '/';
    public const UNION_TYPE_NAME_PREFIX = '*';
}
