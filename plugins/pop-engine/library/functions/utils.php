<?php

function getDomain($url)
{
    $parse = parse_url($url);
    return $parse['scheme'].'://'.$parse['host'];
}
function removeScheme($domain)
{
    $arr = explode("//", $domain);
    return count($arr) == 1 ? $arr[0] : $arr[1];
}
function removeDomain($url)
{
    return substr($url, strlen(getDomain($url)));
}

function fullUrl()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function arrayFlatten(array $array)
{
    $return = array();
    array_walk_recursive(
        $array,
        function ($a) use (&$return) {
            $return[] = $a;
        }
    );
    return $return;
}

function doingPost()
{
    return ('POST' == $_SERVER['REQUEST_METHOD']);
}

// Returns true if this is an Ajax call
function doingAjax()
{
    $doingAjax = defined('DOING_AJAX') && DOING_AJAX;
    return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('gd_doing_ajax', $doingAjax);
}

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

// Taken from https://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it
function deleteDir($dirPath)
{
    // if (!is_dir($dirPath)) {
    //     throw new InvalidArgumentException("$dirPath must be a directory");
    // }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    @rmdir($dirPath);
}

function arrayIsSubset($maybe_subset, $set)
{
    return $maybe_subset == arrayIntersectAssocRecursive($maybe_subset, $set);
}

// This function is an implementation of a recursive `array_intersect_assoc`, so that in the RouteModuleProcessor we can ask for conditions recursively (eg: array('routing-state' => array('post-type' => 'event')))
// Modified from https://stackoverflow.com/questions/4627076/php-question-how-to-array-intersect-assoc-recursively
function arrayIntersectAssocRecursive(&$arr1, &$arr2)
{
    if (!is_array($arr1) || !is_array($arr2)) {
        return (string) $arr1 == (string) $arr2 ? $arr1 : null;
    }
    
    $commonkeys = array_intersect(array_keys($arr1), array_keys($arr2));
    $ret = array();
    foreach ($commonkeys as $key) {
        $value = arrayIntersectAssocRecursive($arr1[$key], $arr2[$key]);
        if (!is_null($value)) {
            $ret[$key] = $value;
        }
    }

    // If no values, then must return null, so it's avoided when asking is_null() above
    return $ret ? $ret : null;
}
