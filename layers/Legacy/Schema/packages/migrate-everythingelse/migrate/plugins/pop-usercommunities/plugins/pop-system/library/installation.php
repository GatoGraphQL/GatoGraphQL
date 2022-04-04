<?php
class PoP_UserCommunities_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-build', $this->installRoles(...));
    }

    public function installRoles()
    {
        $cmsuserrolesapi = \PoPCMSSchema\UserRoles\FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRole(GD_URE_ROLE_COMMUNITY, 'GD Community', array());
    }
}

/**
 * Initialization
 */
new PoP_UserCommunities_Installation();
