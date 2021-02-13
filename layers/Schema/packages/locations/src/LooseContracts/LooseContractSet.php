<?php

declare(strict_types=1);

namespace PoPSchema\Locations\LooseContracts;

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
            'popcomponent:locations:dbcolumn:orderby:locations:name',
        ];
    }
}
