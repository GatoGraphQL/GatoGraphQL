<?php
namespace PoP\Engine;

class GeneralUtils
{
    // Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
	public static function generateRandomString($length = 6, $addtime = true, $characters = 'abcdefghijklmnopqrstuvwxyz')
	{
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }

	    if ($addtime) {
	        $randomString .= time();
	    }
	    return $randomString;
	}

	public static function isError($thing)
	{
		return $thing && $thing instanceof \PoP\Engine\Error;
	}
}
