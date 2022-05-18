<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;

abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);
        // The fields to retrieve are passed through component variation atts, so simply transfer all component variation atts down the line
        $ret[] = [RelationalFieldQueryDataComponentProcessor::class, RelationalFieldQueryDataComponentProcessor::MODULE_LAYOUT_RELATIONALFIELDS, $componentVariation[2] ?? null];
        return $ret;
    }

    public function getFormat(array $componentVariation): ?string
    {
        return Formats::FIELDS;
    }
}
