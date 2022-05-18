<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentRoutingProcessors;

use PoP_ConfigurationComponentModel_Module_Processor_Elements;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $ret[] = [
            'component' => [PoP_ConfigurationComponentModel_Module_Processor_Elements::class, PoP_ConfigurationComponentModel_Module_Processor_Elements::COMPONENT_EMPTY],
        ];

        return $ret;
    }
}
