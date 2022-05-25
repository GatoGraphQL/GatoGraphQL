<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class MimeTypesFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(): string
    {
        return 'mime-types';
    }
}
