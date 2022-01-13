<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_CommonUserRoles_Installation
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction('PoP:system-build', array($this, 'installRoles'));
    }

    public function installRoles()
    {
        $cmsuserrolesapi = \PoPSchema\UserRoles\FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRole(GD_URE_ROLE_INDIVIDUAL, 'GD Individual', array());
        $cmsuserrolesapi->addRole(GD_URE_ROLE_ORGANIZATION, 'GD Organization', array());
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRoles_Installation();
