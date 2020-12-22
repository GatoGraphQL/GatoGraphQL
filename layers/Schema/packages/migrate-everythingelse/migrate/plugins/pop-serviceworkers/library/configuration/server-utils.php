<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

class PoP_ServiceWorkers_ServerUtils
{
    public static function disableServiceworkers()
    {
        if (PoP_WebPlatform_ServerUtils::disableJs()) {
            return false;
        }

        // Allow to override the configuration
        $override = ComponentModelComponentConfiguration::getOverrideConfiguration('disable-sw');
        if (!is_null($override)) {
            return $override;
        }

        return getenv('DISABLE_SERVICE_WORKERS') !== false ? strtolower(getenv('DISABLE_SERVICE_WORKERS')) == "true" : false;
    }
}
