<?php

// Allow to override this function
if (!function_exists('pop_fileupload_userphoto_avatarsizes')) {

	function pop_fileupload_userphoto_avatarsizes() {

		// return array(16, 24, 26, 32, 40, 50, 60, 64, 82, 100, 120, 150);
		return array(16, 50, 100, 150);
	}
}