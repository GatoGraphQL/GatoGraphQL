<?php

declare(strict_types=1);

namespace PoP\Engine\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredHooks(): array
    {
        return [
            // Actions
            'popcms:init',
            'popcms:shutdown',
            'popcms:componentInstalled',
            'popcms:componentUninstalled',
            'popcms:componentInstalledOrUninstalled',
        ];
    }

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
