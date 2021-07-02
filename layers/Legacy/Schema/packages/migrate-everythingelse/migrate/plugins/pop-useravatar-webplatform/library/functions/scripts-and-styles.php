<?php
use PoP\Hooks\Facades\HooksAPIFacade;

function popUseravatarGetLocaleJsfile()
{
    return POP_USERAVATARWEBPLATFORM_URL.'/js/locales/fileupload/'.popUseravatarGetLocaleJsfilename();
}

function popUseravatarGetLocaleJsfilename()
{
    return HooksAPIFacade::getInstance()->applyFilters('gd_fileupload-userphoto_locale:filename', 'locale.js');
}
