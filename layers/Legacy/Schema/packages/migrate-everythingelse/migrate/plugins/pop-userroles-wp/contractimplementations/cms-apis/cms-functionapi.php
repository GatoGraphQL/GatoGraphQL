<?php
namespace PoPCMSSchema\UserRoles\WP;

class FunctionAPI extends \PoPCMSSchema\UserRoles\FunctionAPI_Base
{
    public function addRole($role, $role_name, $capabilities = array())
    {
        add_role($role, $role_name, $capabilities);
    }
    public function addRoleToUser($user_id, $role)
    {
        $user = get_user_by('id', $user_id);
        $user->add_role($role);
    }
    public function removeRoleFromUser($user_id, $role)
    {
        $user = get_user_by('id', $user_id);
        $user->remove_role($role);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
