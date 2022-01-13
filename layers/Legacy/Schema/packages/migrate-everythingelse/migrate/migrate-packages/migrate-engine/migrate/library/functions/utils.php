<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function removeScheme($domain)
{
    $arr = explode("//", $domain);
    return count($arr) == 1 ? $arr[0] : $arr[1];
}

function arrayFlatten(array $array, bool $firstLevelOnly = false)
{
    $return = array();
    if ($firstLevelOnly) {
        array_walk(
            $array,
            function ($a) use (&$return) {
                $return[] = $a;
            }
        );
    } else {
        array_walk_recursive(
            $array,
            function ($a) use (&$return) {
                $return[] = $a;
            }
        );
    }
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
    return HooksAPIFacade::getInstance()->applyFilters('gd_doing_ajax', $doingAjax);
}

function multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}
