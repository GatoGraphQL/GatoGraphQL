<?php

require_once 'routes.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
    'popcms:init', 
    'popCmsmodelInitConstants', 
    10000
);
function popCmsmodelInitConstants()
{
    include_once 'constants.php';
}
