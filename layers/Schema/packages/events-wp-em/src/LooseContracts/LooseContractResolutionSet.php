<?php

declare(strict_types=1);

namespace PoPSchema\EventsWPEM\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts()
    {
        $this->nameResolver->implementNames([
            'popcomponent:events:dbcolumn:orderby:events:startdate' => 'event_start_date',
        ]);
    }
}
