<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

class JSONDataStructureFormatter extends AbstractJSONDataStructureFormatter
{
    public function getName(): string
    {
        return 'json';
    }
}
