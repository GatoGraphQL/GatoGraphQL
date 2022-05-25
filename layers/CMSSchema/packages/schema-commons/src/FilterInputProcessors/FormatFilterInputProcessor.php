<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class FormatFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'format';
    }
}
