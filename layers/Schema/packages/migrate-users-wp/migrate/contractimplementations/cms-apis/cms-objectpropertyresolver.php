<?php
namespace PoPSchema\Users\WP;

class ObjectPropertyResolver extends \PoPSchema\Users\ObjectPropertyResolver_Base
{
    public function getUserLogin($user)
    {
        return $user->user_login;
    }
    public function getUserNicename($user)
    {
        return $user->user_nicename;
    }
    public function getUserSlug($user)
    {
        return $user->user_nicename;
    }
    public function getUserDisplayName($user)
    {
        return $user->display_name;
    }
    public function getUserFirstname($user)
    {
        return $user->user_firstname;
    }
    public function getUserLastname($user)
    {
        return $user->user_lastname;
    }
    public function getUserEmail($user)
    {
        return $user->user_email;
    }
    public function getUserId($user)
    {
        return $user->ID;
    }
    public function getUserDescription($user)
    {
        return $user->description;
    }
    public function getUserWebsiteUrl($user)
    {
        return $user->user_url;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
