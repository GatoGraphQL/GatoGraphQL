<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;

abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    protected function getInnerSubmodules(array $component): array
    {
        $ret = parent::getInnerSubmodules($component);
        // The fields to retrieve are passed through component atts, so simply transfer all component atts down the line
        $ret[] = [RelationalFieldQueryDataComponentProcessor::class, RelationalFieldQueryDataComponentProcessor::MODULE_LAYOUT_RELATIONALFIELDS, $component[2] ?? null];
        return $ret;
    }

    public function getFormat(array $component): ?string
    {
        return Formats::FIELDS;
    }
}
