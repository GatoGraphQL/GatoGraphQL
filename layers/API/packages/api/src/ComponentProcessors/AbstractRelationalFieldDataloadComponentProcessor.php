<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;

abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    protected function getInnerSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);
        // The fields to retrieve are passed through component atts, so simply transfer all component atts down the line
        $ret[] = new \PoP\ComponentModel\Component\Component(RelationalFieldQueryDataComponentProcessor::class, RelationalFieldQueryDataComponentProcessor::COMPONENT_LAYOUT_RELATIONALFIELDS, $component->atts);
        return $ret;
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return Formats::FIELDS;
    }
}
