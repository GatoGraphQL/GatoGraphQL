<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Functions dealing with Avatars
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function pop_useravatar_get_locale_jsfile() {

	$js_folder = POP_USERAVATAR_URI.'/js';
	return apply_filters('gd_fileupload-userphoto_locale', $js_folder .'/locales/fileupload/locale.js');
}