<?php

/**
 * AAL Dashboard options
 */
// Show the notifications for Profile users in the Dashboard's Activity Log table
\PoP\Root\App::addFilter('aal_init_caps', 'popAalInitCaps');
function popAalInitCaps($caps_settings)
{
    $caps_settings['administrator'][] = GD_ROLE_PROFILE;
    $caps_settings['editor'][] = GD_ROLE_PROFILE;
    return $caps_settings;
}
