<?php

declare(strict_types=1);

use PoP\ComponentModel\Facades\Engine\EngineFacade;

$engine = EngineFacade::getInstance();
$engine->generateDataAndPrepareResponse();
