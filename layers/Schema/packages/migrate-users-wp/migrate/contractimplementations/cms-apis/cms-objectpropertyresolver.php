<?php
namespace PoPSchema\Users\WP;

class ObjectPropertyResolver extends \PoPSchema\Users\ObjectPropertyResolver_Base
{
    public function getUserNicename($user)
    {
        return $user->user_nicename;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
