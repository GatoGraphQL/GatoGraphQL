<?php

declare(strict_types=1);

use PoP\Engine\Facades\Engine\EngineFacade;

$engine = EngineFacade::getInstance();
$engine->generateDataAndPrepareResponse();

// Send the Response to the client
require_once __DIR__ . '/SendResponse.php';
