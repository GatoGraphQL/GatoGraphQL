<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_Coauthors_LooseContracts extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredHooks(): array
    {
        return [
            // Filters
            'popcomponent:coauthors:supportedposttypes',
        ];
    }
}

/**
 * Initialize
 */
new PoP_Coauthors_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

