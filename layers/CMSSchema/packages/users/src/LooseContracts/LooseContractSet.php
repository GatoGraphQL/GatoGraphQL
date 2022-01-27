<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\LooseContracts;

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
            'popcms:dbcolumn:orderby:users:name',
            'popcms:dbcolumn:orderby:users:id',
            'popcms:dbcolumn:orderby:users:registrationdate',
        ];
    }
}
