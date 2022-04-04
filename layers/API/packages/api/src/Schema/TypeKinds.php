<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

class TypeKinds
{
    public final const OBJECT = 'Object';
    public final const INTERFACE = 'Interface';
    public final const UNION = 'Union';
    public final const SCALAR = 'Scalar';
    public final const ENUM = 'Enum';
    public final const INPUT_OBJECT = 'InputObject';
}
