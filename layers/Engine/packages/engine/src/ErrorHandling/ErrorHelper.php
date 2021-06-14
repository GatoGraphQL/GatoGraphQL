<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

class ErrorHelper implements ErrorHelperInterface
{
    public function __construct(protected ErrorManagerInterface $errorManager)
    {
    }

    public function returnResultOrConvertError(mixed $result): mixed
    {
        if ($this->errorManager->isCMSError($result)) {
            return $this->errorManager->convertFromCMSToPoPError((object) $result);
        }
        return $result;
    }
}
