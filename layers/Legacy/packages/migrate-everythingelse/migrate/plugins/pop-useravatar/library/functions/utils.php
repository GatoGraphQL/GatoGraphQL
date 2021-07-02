<?php

// Taken from https://secure.php.net/manual/en/function.copy.php
function recurseCopy($src, $dst)
{
    if (file_exists($src) && is_dir($src)) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}

function delTree($dir)
{

    // Delete the "/" from the dir if it is included
    if (substr($dir, -1) == '/') {
        $dir = substr($dir, 0, strlen($dir) - 1);
    }
    
    if (file_exists($dir) && is_dir($dir)) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    return false;
}
