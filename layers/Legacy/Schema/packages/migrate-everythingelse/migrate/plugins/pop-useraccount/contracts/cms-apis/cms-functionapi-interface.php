<?php
namespace PoP\UserAccount;

interface FunctionAPI
{
    public function checkPassword($user_id, $password);
    /**
     * @throws GenericClientException
     */
    public function checkPasswordResetKey($key, $login);
    public function resetPassword($user, $pwd);
    public function getPasswordResetKey($user_data);
    public function getLoginURL();
    public function getLogoutURL();
    public function getLostPasswordURL();
}
