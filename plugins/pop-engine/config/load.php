<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
\PoP\CMS\HooksAPI_Factory::getInstance()->addAction('init', 'popengineInitConstants', 10000);
function popengineInitConstants()
{
    include_once 'constants.php';
}
