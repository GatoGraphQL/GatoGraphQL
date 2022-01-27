<?php

declare(strict_types=1);

namespace PoPAPI\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\DataStructureFormatters\XMLDataStructureFormatterTrait;

class XMLMirrorQueryDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    use XMLDataStructureFormatterTrait;

    public function getName(): string
    {
        return 'xml';
    }
}
