<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentRoutingProcessors;

use PoP\ComponentModel\ModuleProcessors\RootModuleProcessors;
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
            'module' => [RootModuleProcessors::class, RootModuleProcessors::MODULE_EMPTY],
        ];

        return $ret;
    }
}
