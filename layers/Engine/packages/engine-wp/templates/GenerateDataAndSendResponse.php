<?php

use PoP\Engine\Facades\Engine\EngineFacade;
use PoP\Root\App;

$engine = EngineFacade::getInstance();
$engine->generateDataAndPrepareResponse();

// Send the Response to the client
echo App::getResponse()->getContent();
App::getResponse()->sendHeaders();
