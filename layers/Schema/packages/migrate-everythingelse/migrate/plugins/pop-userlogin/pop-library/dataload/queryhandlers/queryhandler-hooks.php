<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

define('GD_DATALOAD_GETUSERINFO', 'getuserinfo');

define('GD_DATALOAD_USER', 'user');
define('GD_DATALOAD_USER_LOGGEDIN', 'logged-in');
define('GD_DATALOAD_USER_ID', 'id');
define('GD_DATALOAD_USER_NAME', 'name');
define('GD_DATALOAD_USER_URL', 'url');

class PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine:session-meta',
            array($this, 'getSessionMeta')
        );
    }

    public function getSessionMeta($meta)
    {

        // Get the user info? (used for pages where user logged in is needed. Generally same as with checkpoints)
        if (PoP_UserLogin_Utils::getUserInfo()) {
            $vars = ApplicationState::getVars();
            $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
            $user_id = '';
            $user_name = '';
            $user_url = '';
            $user_logged_in = $vars['global-userstate']['is-user-logged-in'];
            if ($user_logged_in) {
                $user_id = $vars['global-userstate']['current-user-id'];
                $user_name = $cmsusersapi->getUserDisplayName($user_id);
                $user_url = $cmsusersapi->getUserURL($user_id);
            }
            
            // Allow PoP Application User Avatar to add the user avatar
            $meta[GD_DATALOAD_USER] = HooksAPIFacade::getInstance()->applyFilters(
                'PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks:user-feedback',
                array(
                    GD_DATALOAD_USER_LOGGEDIN => $user_logged_in,
                    GD_DATALOAD_USER_ID => $user_id,
                    GD_DATALOAD_USER_NAME => $user_name,
                    GD_DATALOAD_USER_URL => $user_url,
                )
            );
        }
        
        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_UserLogin_DataLoad_QueryInputOutputHandler_Hooks();
