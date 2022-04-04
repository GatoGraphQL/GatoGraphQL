<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class TypeKinds
{
    public final const SCALAR = 'SCALAR';
    public final const OBJECT = 'OBJECT';
    public final const INTERFACE = 'INTERFACE';
    public final const UNION = 'UNION';
    public final const ENUM = 'ENUM';
    public final const INPUT_OBJECT = 'INPUT_OBJECT';
    public final const LIST = 'LIST';
    public final const NON_NULL = 'NON_NULL';
}
