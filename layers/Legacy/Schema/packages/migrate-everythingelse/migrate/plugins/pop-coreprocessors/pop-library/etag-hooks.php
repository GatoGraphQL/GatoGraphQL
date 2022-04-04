<?php

class PoP_Core_EtagHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            '\PoP\ComponentModel\Engine:etag_header:commoncode',
            $this->getCommoncode(...)
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
