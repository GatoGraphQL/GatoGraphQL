<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

class MutationSchemes
{
    public const STANDARD = 'standard';
    public const NESTED_WITH_REDUNDANT_ROOT_FIELDS = 'nested';
    public const NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS = 'lean_nested';
}
