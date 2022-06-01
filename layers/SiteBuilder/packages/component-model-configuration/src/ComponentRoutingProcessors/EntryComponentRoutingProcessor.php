<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoP_ConfigurationComponentModel_Module_Processor_Elements;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<array<string,mixed>>
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
