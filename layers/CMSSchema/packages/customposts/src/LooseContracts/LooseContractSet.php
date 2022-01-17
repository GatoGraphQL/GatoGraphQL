<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\LooseContracts;

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
            'popcms:dbcolumn:orderby:customposts:date',
            'popcms:dbcolumn:orderby:customposts:modified',
            'popcms:dbcolumn:orderby:customposts:id',
        ];
    }
}
