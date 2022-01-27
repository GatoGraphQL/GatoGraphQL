<?php

\PoP\Root\App::addFilter(
    'GD_FileUpload_UserPhoto:action-url',
    'popthemeFileuploadUserphotoAwsActionurl',
    50
);
function popthemeFileuploadUserphotoAwsActionurl($url)
{
    return GD_FileUpload_Picture_Utils::getActionUrlFromBasedir(dirname(dirname(__FILE__)));
}
