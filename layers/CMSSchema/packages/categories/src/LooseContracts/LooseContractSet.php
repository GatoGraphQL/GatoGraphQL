<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\LooseContracts;

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
            'popcms:dbcolumn:orderby:categories:count',
            'popcms:dbcolumn:orderby:categories:id',
        ];
    }
}
