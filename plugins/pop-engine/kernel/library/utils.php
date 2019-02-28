<?php

// Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString($length = 6, $addtime = true, $characters = 'abcdefghijklmnopqrstuvwxyz')
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
