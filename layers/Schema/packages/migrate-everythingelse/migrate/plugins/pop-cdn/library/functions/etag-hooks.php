<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_CDN_Hooks
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
        $commoncode = preg_replace('/"'.POP_CDN_THUMBPRINTVALUES.'":{.*?},?/', '', $commoncode);
        return $commoncode;
    }
}

/**
 * Initialization
 */
new PoP_CDN_Hooks();
