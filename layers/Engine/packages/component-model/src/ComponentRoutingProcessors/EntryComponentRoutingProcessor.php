<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentRoutingProcessors;

use PoP\ComponentModel\ComponentProcessors\RootComponentProcessors;
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
            'component-variation' => [RootComponentProcessors::class, RootComponentProcessors::MODULE_EMPTY],
        ];

        return $ret;
    }
}
