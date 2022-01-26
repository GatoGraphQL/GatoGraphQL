<?php

declare(strict_types=1);

use PoP\Engine\Facades\Engine\EngineFacade;

$engine = EngineFacade::getInstance();
$engine->generateDataAndPrepareResponse();
