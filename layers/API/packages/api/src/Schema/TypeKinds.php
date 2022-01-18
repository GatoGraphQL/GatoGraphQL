<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

class TypeKinds
{
    public const OBJECT = 'Object';
    public const INTERFACE = 'Interface';
    public const UNION = 'Union';
    public const SCALAR = 'Scalar';
    public const ENUM = 'Enum';
    public const INPUT_OBJECT = 'InputObject';
}
