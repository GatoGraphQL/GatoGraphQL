<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectModels;

class RootObject
{
    public const ID = 'root';
    public function getID(): string
    {
        return self::ID;
    }
}
