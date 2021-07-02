<?php
namespace PoP\EditUsers\WP;

use PoP\Engine\Facades\ErrorHandling\ErrorHelperFacade;

class FunctionAPI extends \PoP\EditUsers\FunctionAPI_Base
{
    public function insertUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_insert_user($user_data);
        $errorHelper = ErrorHelperFacade::getInstance();
        return $errorHelper->returnResultOrConvertError($result);
    }
    public function updateUser($user_data)
    {
        $this->convertQueryArgsFromPoPToCMSForInsertUpdateUser($user_data);
        $result = wp_update_user($user_data);
        $errorHelper = ErrorHelperFacade::getInstance();
        return $errorHelper->returnResultOrConvertError($result);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
