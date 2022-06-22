<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentRoutingProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\RootComponentProcessors;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;

class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        $ret = array();

        $ret[] = [
            'component' => new Component(
                RootComponentProcessors::class,
                RootComponentProcessors::COMPONENT_EMPTY
            ),
        ];

        return $ret;
    }
}
