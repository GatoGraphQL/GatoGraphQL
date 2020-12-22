<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\Misc\RequestUtils;

class PoP_SSR_ServerUtils
{
    public static function disableServerSideRendering()
    {
        // If disabling the JS, then we can only do server-side rendering
        if (PoP_WebPlatform_ServerUtils::disableJs()) {
            return false;
        }

        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('disable-serverside-rendering');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('DISABLE_SERVER_SIDE_RENDERING') !== false ? strtolower(getenv('DISABLE_SERVER_SIDE_RENDERING')) == "true" : false;
    }

    public static function removeDatabasesFromOutput()
    {
        // We only remove the code in the server-side rendering, when first loading the website. If this is not the case,
        // then there is no need for this functionality
        if (!RequestUtils::loadingSite() || self::disableServerSideRendering()) {
            return false;
        }

        return getenv('REMOVE_DATABASES_FROM_OUTPUT') !== false ? strtolower(getenv('REMOVE_DATABASES_FROM_OUTPUT')) == "true" : false;
    }

    public static function includeScriptsAfterHtml()
    {
        if (self::disableServerSideRendering()) {
            return false;
        }

        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('scripts-end');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('INCLUDE_SCRIPTS_AFTER_HTML') !== false ? strtolower(getenv('INCLUDE_SCRIPTS_AFTER_HTML')) == "true" : false;
    }
}
