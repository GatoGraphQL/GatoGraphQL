<?php

declare(strict_types=1);

namespace PoP\Engine\Error;

use PoP\BasicService\BasicServiceTrait;

class ErrorHelper implements ErrorHelperInterface
{
    use BasicServiceTrait;

    private ?ErrorManagerInterface $errorManager = null;

    final public function setErrorManager(ErrorManagerInterface $errorManager): void
    {
        $this->errorManager = $errorManager;
    }
    final protected function getErrorManager(): ErrorManagerInterface
    {
        return $this->errorManager ??= $this->instanceManager->getInstance(ErrorManagerInterface::class);
    }

    public function returnResultOrConvertError(mixed $result): mixed
    {
        if ($this->getErrorManager()->isCMSError($result)) {
            return $this->getErrorManager()->convertFromCMSToPoPError((object) $result);
        }
        return $result;
    }
}
