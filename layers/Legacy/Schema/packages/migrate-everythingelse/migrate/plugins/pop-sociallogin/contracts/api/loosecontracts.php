<?php
use PoP\LooseContracts\AbstractLooseContractSet;

class PoP_SocialLogin_LooseContracts extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredHooks(): array
    {
        return [
            // Actions
            'popcomponent:sociallogin:usercreated',
        ];
    }
}

/**
 * Initialize
 */
new PoP_SocialLogin_LooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

