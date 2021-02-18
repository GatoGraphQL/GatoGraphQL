<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

class DefaultDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'default';
    }
}
