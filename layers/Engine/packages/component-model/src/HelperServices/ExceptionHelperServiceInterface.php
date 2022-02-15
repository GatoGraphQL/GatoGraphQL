<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use Exception;

interface ExceptionHelperServiceInterface
{
    public function sendExceptionMessageToClient(Exception $e): bool;
}
