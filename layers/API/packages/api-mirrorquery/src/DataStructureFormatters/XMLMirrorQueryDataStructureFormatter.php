<?php

declare(strict_types=1);

namespace PoP\APIMirrorQuery\DataStructureFormatters;

use PoP\ComponentModel\DataStructure\XMLDataStructureFormatterTrait;

class XMLMirrorQueryDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    use XMLDataStructureFormatterTrait;

    public function getName(): string
    {
        return 'xml';
    }
}
