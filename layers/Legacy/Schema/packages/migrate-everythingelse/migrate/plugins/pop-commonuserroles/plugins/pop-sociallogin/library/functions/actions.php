<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * User Communities
 */
// Add the Profile and Individual Roles when creating a new User Account with WSL
\PoP\Root\App::getHookManager()->addAction('popcomponent:sociallogin:usercreated', 'gdWslUreHookProcessLoginAfterWpInsertUser');
function gdWslUreHookProcessLoginAfterWpInsertUser($user_id)
{
    // GD_ROLE_PROFILE alredy added. Now add the Individual role
    $cmsuserrolesapi = \PoPSchema\UserRoles\FunctionAPIFactory::getInstance();
    $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_INDIVIDUAL);
}
