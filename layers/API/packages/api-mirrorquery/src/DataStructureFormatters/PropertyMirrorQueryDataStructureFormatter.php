<?php

declare(strict_types=1);

namespace PoPAPI\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\DataStructureFormatters\PropertyDataStructureFormatterTrait;

class PropertyMirrorQueryDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    use PropertyDataStructureFormatterTrait;

    public function getName(): string
    {
        return 'props';
    }
}
