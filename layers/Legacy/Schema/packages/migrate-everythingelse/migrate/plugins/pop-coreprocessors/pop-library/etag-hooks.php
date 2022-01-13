<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Core_EtagHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine:etag_header:commoncode',
            array($this, 'getCommoncode')
        );
    }

    public function getCommoncode($commoncode)
    {

        // Remove the thumbprint values from the ETag
        $commoncode = preg_replace('/"'.POP_KEYS_THUMBPRINT.'":[0-9]+,?/', '', $commoncode);
        return $commoncode;
    }
}

/**
 * Initialization
 */
new PoP_Core_EtagHooks();
