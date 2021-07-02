<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_AddLocations_LooseContracts extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            // Options
            'popcomponent:addlocations:option:locationDefaultCountry',
        ];
    }
}

/**
 * Initialize
 */
new PoP_AddLocations_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

