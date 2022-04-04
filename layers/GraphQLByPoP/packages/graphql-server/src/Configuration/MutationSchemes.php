<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Configuration;

class MutationSchemes
{
    public final const STANDARD = 'standard';
    public final const NESTED_WITH_REDUNDANT_ROOT_FIELDS = 'nested';
    public final const NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS = 'lean_nested';
}
