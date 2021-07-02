<?php
use PoP\ComponentModel\Misc\GeneralUtils;

class PoP_URE_ModuleManager_Utils
{
    public static function addSource($url, $source)
    {
        // Add the source only if it's not the default one
        if ($source != gdUreGetDefaultContentsource()) {
	            $url = GeneralUtils::addQueryArgs([
            	GD_URLPARAM_URECONTENTSOURCE => $source, 
            ], $url);
        }

        return $url;
    }
}
