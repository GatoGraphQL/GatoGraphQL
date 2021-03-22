<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use PoP\Engine\Facades\ErrorHandling\ErrorManagerFacade;

class ErrorUtils
{
    public static function returnResultOrConvertError(object $result): object
    {
        $errorManager = ErrorManagerFacade::getInstance();
        if ($errorManager->isCMSError($result)) {
            return $errorManager->convertFromCMSToPoPError($result);
        }
        return $result;
    }
}
