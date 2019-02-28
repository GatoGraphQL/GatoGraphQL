<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'popCmsmodelInitConstants', 10000);
function popCmsmodelInitConstants()
{
    include_once 'constants.php';
}
