<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:tags:count',
            'popcms:dbcolumn:orderby:tags:id',
        ];
    }
}
