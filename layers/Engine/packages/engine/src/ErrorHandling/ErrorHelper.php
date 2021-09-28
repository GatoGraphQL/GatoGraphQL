<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

class ErrorHelper implements ErrorHelperInterface
{
    protected ErrorManagerInterface $errorManager;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireErrorHelper(ErrorManagerInterface $errorManager)
    {
        $this->errorManager = $errorManager;
    }

    public function returnResultOrConvertError(mixed $result): mixed
    {
        if ($this->errorManager->isCMSError($result)) {
            return $this->errorManager->convertFromCMSToPoPError((object) $result);
        }
        return $result;
    }
}
