<?php

declare(strict_types=1);

namespace PoP\Engine\LooseContracts;

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
            'popcms:option:dateFormat',
            'popcms:option:charset',
            'popcms:option:gmtOffset',
            'popcms:option:timezone',
        ];
    }
}
