<?php
// Taken from https://secure.php.net/manual/en/function.realpath.php#84012
function getAbsolutePath($path)
{
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) {
            continue;
        }
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    // Change Leo: Add the initial "/" to make the path absolute, if it is originally so
    return (substr($path, 0, 1) == DIRECTORY_SEPARATOR ? DIRECTORY_SEPARATOR : '').implode(DIRECTORY_SEPARATOR, $absolutes);
    // return implode(DIRECTORY_SEPARATOR, $absolutes);
}
