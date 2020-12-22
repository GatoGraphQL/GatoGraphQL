<?php

declare(strict_types=1);

namespace PoP\Engine\ObjectModels;

class Root
{
    public const ID = 'root';
    public function getID()
    {
        return self::ID;
    }
}
