<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;

abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);
        // The fields to retrieve are passed through module atts, so simply transfer all module atts down the line
        $ret[] = [RelationalFieldQueryDataComponentProcessor::class, RelationalFieldQueryDataComponentProcessor::MODULE_LAYOUT_RELATIONALFIELDS, $module[2] ?? null];
        return $ret;
    }

    public function getFormat(array $module): ?string
    {
        return Formats::FIELDS;
    }
}
