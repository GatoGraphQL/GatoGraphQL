<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

class PoP_WebPlatform_ServerUtils
{
    public static function loadDynamicallyGeneratedResourceFiles()
    {
        return PoP_HTMLCSSPlatform_ServerUtils::loadDynamicallyGeneratedResourceFiles();
    }

    public static function useMinifiedResources()
    {
        return PoP_HTMLCSSPlatform_ServerUtils::useMinifiedResources();
    }

    public static function accessExternalcdnResources()
    {
        return PoP_HTMLCSSPlatform_ServerUtils::accessExternalcdnResources();
    }

    public static function useBundledResources()
    {
        return PoP_HTMLCSSPlatform_ServerUtils::useBundledResources();
    }

    public static function useLocalStorage()
    {
        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('localstorage');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('USE_LOCAL_STORAGE') !== false ? strtolower(getenv('USE_LOCAL_STORAGE')) == "true" : false;
    }

    public static function useAppshell()
    {
        if (self::disableJs()) {
            return false;
        }

        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('appshell');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('USE_APPSHELL') !== false ? strtolower(getenv('USE_APPSHELL')) == "true" : false;
    }

    public static function useProgressiveBooting()
    {
        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('progressive-booting');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('USE_PROGRESSIVE_BOOTING') !== false ? strtolower(getenv('USE_PROGRESSIVE_BOOTING')) == "true" : false;
    }

    public static function disableJs()
    {
        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('disable-js');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('DISABLE_JS') !== false ? strtolower(getenv('DISABLE_JS')) == "true" : false;
    }
}
