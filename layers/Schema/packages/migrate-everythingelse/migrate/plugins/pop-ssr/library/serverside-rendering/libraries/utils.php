<?php

// Taken from https://stackoverflow.com/questions/1734250/what-is-the-equivalent-of-javascripts-encodeuricomponent-in-php
function encodeURIComponent($str)
{
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

global $counter;
$counter = 0;
function counterNext()
{
    global $counter;
    $counter++;
    return $counter;
}
