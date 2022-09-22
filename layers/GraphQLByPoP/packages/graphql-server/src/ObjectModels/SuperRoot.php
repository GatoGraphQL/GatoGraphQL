<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class SuperRoot
{
    public final const ID = 'super-root';

    public function getID(): string
    {
        return self::ID;
    }
}
