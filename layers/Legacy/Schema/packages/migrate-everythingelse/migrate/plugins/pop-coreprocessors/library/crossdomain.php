<?php

// Add support for Cross-Domain to Plupload
\PoP\Root\App::addFilter('plupload_default_settings', 'popCorePluploadDefaultSettings');
function popCorePluploadDefaultSettings($defaults)
{
    
    // Allow to send cookies, so we can upload files with our loggedin user
    $defaults['required_features'] = "send_browser_cookies";

    return $defaults;
}
