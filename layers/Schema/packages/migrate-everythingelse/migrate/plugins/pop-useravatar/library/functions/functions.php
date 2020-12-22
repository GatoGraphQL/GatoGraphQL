<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

class GD_FileUpload_Picture_Utils
{
    protected static $action_url;

    public static function getActionUrl()
    {

        // Initialize
        if (is_null(self::$action_url)) {
            self::$action_url = HooksAPIFacade::getInstance()->applyFilters(
                'GD_FileUpload_UserPhoto:action-url',
                self::getActionUrlFromBasedir(dirname(dirname(dirname(__FILE__))))
            );
        }

        return self::$action_url;
    }

    public static function getActionUrlFromBasedir($basedir)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return GeneralUtils::maybeAddTrailingSlash($cmsengineapi->getSiteURL()).str_replace(ABSPATH, '', $basedir).'/directaccess-library/fileupload-userphoto/server/index.php';
    }

    public static function getFileuploadUserPath($user_id)
    {
        // Create a randomized string for the user_id, but which always produces the same result
        return hash('md5', 'fileupload'.$user_id);
    }

    public static function getFileuploadUrl($user_id)
    {
        return GeneralUtils::addQueryArgs([
            'upload_path' => self::getFileuploadUserPath($user_id),
        ], self::getActionUrl());
    }
}
