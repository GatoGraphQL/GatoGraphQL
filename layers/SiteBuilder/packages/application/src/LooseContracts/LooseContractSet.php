<?php

declare(strict_types=1);

namespace PoP\Application\LooseContracts;

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
            'popcms:option:limit',
        ];
    }
}
