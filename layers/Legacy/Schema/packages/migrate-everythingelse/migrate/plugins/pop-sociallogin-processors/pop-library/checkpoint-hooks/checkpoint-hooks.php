<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class WSL_CheckpointHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_UserLogin_Module_Processor_Blocks:extra-checkpoint-msgs:change-pwd',
            array($this, 'getCheckpointMessages')
        );
    }

    public function getCheckpointMessages($checkpoint_messages)
    {

        // Not available to WSL users
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $checkpoint_messages['sociallogin-user'] = sprintf(
            TranslationAPIFacade::getInstance()->__('Only %s accounts can change their password.', 'pop-coreprocessors'),
            $cmsapplicationapi->getSiteName()
        );

        return $checkpoint_messages;
    }
}

/**
 * Initialization
 */
new WSL_CheckpointHooks();
