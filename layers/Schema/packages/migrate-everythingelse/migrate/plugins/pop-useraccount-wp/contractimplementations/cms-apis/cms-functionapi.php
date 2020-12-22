<?php
namespace PoP\UserAccount\WP;

use PoP\Engine\ErrorHandling\ErrorUtils;

class FunctionAPI extends \PoP\UserAccount\FunctionAPI_Base
{
    public function getPasswordResetKey($user_data)
    {
        $result = get_password_reset_key($user_data);
        return ErrorUtils::returnResultOrConvertError($result);
    }

    public function checkPassword($user_id, $password)
    {
        $user = get_user_by('id', $user_id);
        $hash = $user->user_pass;
        return wp_check_password($password, $hash);
    }

    public function checkPasswordResetKey($key, $login)
    {
        $result = check_password_reset_key($key, $login);
        return ErrorUtils::returnResultOrConvertError($result);
    }

    protected function convertQueryArgsFromPoPToCMSForInsertUpdateUser(&$query)
    {
        // Convert the parameters
        if (isset($query['id'])) {

            $query['ID'] = $query['id'];
            unset($query['id']);
        }
        if (isset($query['firstname'])) {

            $query['first_name'] = $query['firstname'];
            unset($query['firstname']);
        }
        if (isset($query['lastname'])) {

            $query['last_name'] = $query['lastname'];
            unset($query['lastname']);
        }
        if (isset($query['email'])) {

            $query['user_email'] = $query['email'];
            unset($query['email']);
        }
        if (isset($query['description'])) {
            // Same param name, so do nothing
        }
        if (isset($query['url'])) {

            $query['user_url'] = $query['url'];
            unset($query['url']);
        }
        if (isset($query['role'])) {
            // Same param name, so do nothing
        }
        if (isset($query['password'])) {

            $query['user_pass'] = $query['password'];
            unset($query['password']);
        }
        if (isset($query['login'])) {

            $query['user_login'] = $query['login'];
            unset($query['login']);
        }
    }

    public function resetPassword($user, $pwd)
    {
        return reset_password($user, $pwd);
    }

    public function getLoginURL()
    {
        return wp_login_url();
    }

    public function getLogoutURL()
    {
        return wp_logout_url();
    }

    public function getLostPasswordURL()
    {
        return wp_lostpassword_url();
    }
}

/**
 * Initialize
 */
new FunctionAPI();
