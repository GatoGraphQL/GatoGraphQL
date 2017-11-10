<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions dealing with Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function pop_useravatar_get_locale_jsfile() {

	return POP_USERAVATAR_URI.'/js/locales/fileupload/'.pop_useravatar_get_locale_jsfilename();
}

function pop_useravatar_get_locale_jsfilename() {

	return apply_filters('gd_fileupload-userphoto_locale:filename', 'locale.js');
}