<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function popUseravatarGetLocaleJsfile()
{
    return POP_USERAVATARWEBPLATFORM_URL.'/js/locales/fileupload/'.popUseravatarGetLocaleJsfilename();
}

function popUseravatarGetLocaleJsfilename()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gd_fileupload-userphoto_locale:filename', 'locale.js');
}
