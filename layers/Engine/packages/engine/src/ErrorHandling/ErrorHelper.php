<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use Symfony\Contracts\Service\Attribute\Required;

class ErrorHelper implements ErrorHelperInterface
{
    protected ErrorManagerInterface $errorManager;

    #[Required]
    final public function autowireErrorHelper(ErrorManagerInterface $errorManager): void
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
