<?php
class PoP_CommonUserRoles_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-build', array($this, 'installRoles'));
    }

    public function installRoles()
    {
        $cmsuserrolesapi = \PoPCMSSchema\UserRoles\FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRole(GD_URE_ROLE_INDIVIDUAL, 'GD Individual', array());
        $cmsuserrolesapi->addRole(GD_URE_ROLE_ORGANIZATION, 'GD Organization', array());
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRoles_Installation();
