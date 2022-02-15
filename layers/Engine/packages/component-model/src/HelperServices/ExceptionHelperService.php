<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\Root\Exception\AbstractClientException;

class ExceptionHelperService implements ExceptionHelperServiceInterface
{
    public function sendExceptionMessageToClient(Exception $e): bool
    {
        if ($e instanceof AbstractClientException) {
            return true;
        }
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->sendExceptionErrorMessages();
    }
}
