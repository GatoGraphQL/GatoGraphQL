<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\Constants\Formats;
use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;

abstract class AbstractRelationalFieldDataloadComponentProcessor extends AbstractDataloadComponentProcessor
{
    /**
     * @return Component[]
     */
    protected function getInnerSubcomponents(Component $component): array
    {
        $ret = parent::getInnerSubcomponents($component);
        // The fields to retrieve are passed through component atts, so simply transfer all component atts down the line
        $ret[] = new Component(RelationalFieldQueryDataComponentProcessor::class, RelationalFieldQueryDataComponentProcessor::COMPONENT_LAYOUT_RELATIONALFIELDS, $component->atts);
        return $ret;
    }

    public function getFormat(Component $component): ?string
    {
        return Formats::FIELDS;
    }
}
