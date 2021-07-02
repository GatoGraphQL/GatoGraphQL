<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_KernelHelperCallers
{
    public static function destroyUrl($url, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->destroyUrl($url, $options);
    }

    public static function iffirstload($options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->iffirstload($options);
    }

    public static function interceptAttr($options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->interceptAttr($options);
    }

    public static function generateId($options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->generateId($options);
    }

    public static function lastGeneratedId($options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->lastGeneratedId($options);
    }

    public static function enterTemplate($template, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->enterTemplate($template, $options);
    }

    /* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
    public static function enterModule($prevContext, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->enterModule($prevContext, $options);
    }

    public static function withModule($context, $moduleName, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->withModule($context, $moduleName, $options);
    }

    public static function withSublevel($sublevel, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->withSublevel($sublevel, $options);
    }

    public static function get($db, $index, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->get($db, $index, $options);
    }

    public static function ifget($db, $index, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->ifget($db, $index, $options);
    }

    public static function withget($db, $index, $options)
    {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->withget($db, $index, $options);
    }
}

/**
 * Registration
 */
PoP_SSR_CLI_HelperRegistration::register(PoP_ServerSide_KernelHelperCallers::class);
