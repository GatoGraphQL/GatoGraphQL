<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Add locale to fileupload-userphoto
\PoP\Root\App::getHookManager()->addFilter('gd_fileupload-userphoto_locale:filename', 'gdFileuploadUserphotoLocaleFilename');
function gdFileuploadUserphotoLocaleFilename($filename)
{
    $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
    return 'locale-'.$pluginapi->getCurrentLanguage().'.js';
}
