<?php

$cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
$content_dir = $cmsengineapi->getContentDir();
$content_url = $cmsengineapi->getContentURL();
define('POP_RESOURCES_DIR', $content_dir.'/pop-resources/'.POP_WEBSITE);
define('POP_RESOURCES_URL', $content_url.'/pop-resources/'.POP_WEBSITE);
