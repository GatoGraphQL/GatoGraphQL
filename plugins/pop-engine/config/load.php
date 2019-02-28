<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'popengineInitConstants', 10000);
function popengineInitConstants()
{
    include_once 'constants.php';
}
