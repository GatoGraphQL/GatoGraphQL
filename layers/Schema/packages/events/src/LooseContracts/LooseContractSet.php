<?php

declare(strict_types=1);

namespace PoPSchema\Events\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            // Options
            'popcomponent:events:dbcolumn:orderby:events:startdate',
        ];
    }
}
