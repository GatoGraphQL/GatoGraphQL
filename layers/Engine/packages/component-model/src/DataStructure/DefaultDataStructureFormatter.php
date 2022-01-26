<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructureFormatters\AbstractJSONDataStructureFormatter;

class DefaultDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'default';
    }
}
