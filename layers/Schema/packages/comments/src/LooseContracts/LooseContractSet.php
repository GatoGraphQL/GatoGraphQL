<?php

declare(strict_types=1);

namespace PoPSchema\Comments\LooseContracts;

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
            'popcms:dbcolumn:orderby:comments:date',
            'popcms:dbcolumn:orderby:customposts:comment-count',
        ];
    }
}
