<?php
namespace PoP\EditUsers\WP;

use PoP\Engine\ErrorHandling\ErrorUtils;

class FunctionAPI extends \PoP\EditUsers\FunctionAPI_Base
{
    public function insertUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_insert_user($user_data);
        return ErrorUtils::returnResultOrConvertError($result);
    }
    public function updateUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_update_user($user_data);
        return ErrorUtils::returnResultOrConvertError($result);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
