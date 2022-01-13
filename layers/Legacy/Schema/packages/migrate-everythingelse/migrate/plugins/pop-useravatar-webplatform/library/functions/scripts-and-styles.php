<?php

function popUseravatarGetLocaleJsfile()
{
    return POP_USERAVATARWEBPLATFORM_URL.'/js/locales/fileupload/'.popUseravatarGetLocaleJsfilename();
}

function popUseravatarGetLocaleJsfilename()
{
    return \PoP\Root\App::applyFilters('gd_fileupload-userphoto_locale:filename', 'locale.js');
}
