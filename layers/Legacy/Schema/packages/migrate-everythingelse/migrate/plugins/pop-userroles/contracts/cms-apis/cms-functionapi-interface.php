<?php
namespace PoPCMSSchema\UserRoles;

interface FunctionAPI
{
    public function addRole($role, $role_name, $capabilities = array());
    public function addRoleToUser($user_id, $role);
    public function removeRoleFromUser($user_id, $role);
}
