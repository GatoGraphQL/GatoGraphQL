<?php

namespace PoPCMSSchema\Users\WP;

class FunctionAPI extends \PoPCMSSchema\Users\FunctionAPI_Base
{
    public function getAuthorBase()
    {
        global $wp_rewrite;
        return $wp_rewrite->author_base;
    }
    public function getUserBySlug($value)
    {
        return get_user_by('slug', $value);
    }
    public function getUserRegistrationDate($user_id)
    {
        return get_the_author_meta('user_registered', $user_id);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
