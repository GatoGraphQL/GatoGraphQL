<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

class PoP_HTMLCSSPlatform_ServerUtils
{
    public static function loadDynamicallyGeneratedResourceFiles()
    {
        return getenv('LOAD_DYNAMICALLY_GENERATED_RESOURCE_FILES') !== false ? strtolower(getenv('LOAD_DYNAMICALLY_GENERATED_RESOURCE_FILES')) == "true" : false;
    }

    public static function useMinifiedResources()
    {
        return getenv('USE_MINIFIED_RESOURCES') !== false ? strtolower(getenv('USE_MINIFIED_RESOURCES')) == "true" : false;
    }

    public static function accessExternalcdnResources()
    {
        return getenv('ACCESS_EXTERNAL_CDN_RESOURCES') !== false ? strtolower(getenv('ACCESS_EXTERNAL_CDN_RESOURCES')) == "true" : false;
    }

    public static function throwExceptionOnTemplateError()
    {
        return getenv('THROW_EXCEPTION_ON_TEMPLATE_ERROR') !== false ? strtolower(getenv('THROW_EXCEPTION_ON_TEMPLATE_ERROR')) == "true" : false;
    }

    public static function useBundledResources()
    {
        return getenv('USE_BUNDLED_RESOURCES') !== false ? strtolower(getenv('USE_BUNDLED_RESOURCES')) == "true" : false;
    }
}
