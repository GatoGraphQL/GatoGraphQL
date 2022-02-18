<?php
namespace PoP\EditUsers\WP;

use PoP\Root\Exception\GenericSystemException;
use WP_Error;

class FunctionAPI extends \PoP\EditUsers\FunctionAPI_Base
{
    public function insertUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_insert_user($user_data);
        if ($result instanceof WP_Error) {
            throw new GenericSystemException($result->get_error_message());
        }
        return $result;
    }
    public function updateUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_update_user($user_data);
        if ($result instanceof WP_Error) {
            throw new GenericSystemException($result->get_error_message());
        }
        return $result;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
