<?php

$cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

// Override the pop-serviceworkers folder
define('POP_SERVICEWORKERS_ASSETDESTINATION_DIR', $cmsengineapi->getContentDir().'/pop-serviceworkers/'.POP_WEBSITE);
define('POP_SERVICEWORKERS_ASSETDESTINATION_URL', $cmsengineapi->getContentURL().'/pop-serviceworkers/'.POP_WEBSITE);
