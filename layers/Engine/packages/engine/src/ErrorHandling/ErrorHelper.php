<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use Symfony\Contracts\Service\Attribute\Required;

class ErrorHelper implements ErrorHelperInterface
{
    protected ?ErrorManagerInterface $errorManager = null;

    public function setErrorManager(ErrorManagerInterface $errorManager): void
    {
        $this->errorManager = $errorManager;
    }
    protected function getErrorManager(): ErrorManagerInterface
    {
        return $this->errorManager ??= $this->getInstanceManager()->getInstance(ErrorManagerInterface::class);
    }

    //#[Required]
    final public function autowireErrorHelper(ErrorManagerInterface $errorManager): void
    {
        $this->errorManager = $errorManager;
    }

    public function returnResultOrConvertError(mixed $result): mixed
    {
        if ($this->getErrorManager()->isCMSError($result)) {
            return $this->getErrorManager()->convertFromCMSToPoPError((object) $result);
        }
        return $result;
    }
}
