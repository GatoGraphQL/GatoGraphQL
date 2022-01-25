<?php

use PoP\Engine\Facades\Engine\EngineFacade;
use PoP\Root\App;

$engine = EngineFacade::getInstance();
$engine->generateDataAndPrepareResponse();

// Print the content to the buffer
echo App::getResponse()->getContent();
