<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'GD_FileUpload_UserPhoto:action-url',
    'popthemeFileuploadUserphotoActionurl'
);
function popthemeFileuploadUserphotoActionurl($url)
{
    return GD_FileUpload_Picture_Utils::getActionUrlFromBasedir(dirname(dirname(__FILE__)));
}
