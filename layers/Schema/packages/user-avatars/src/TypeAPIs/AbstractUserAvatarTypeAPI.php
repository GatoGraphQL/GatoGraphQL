<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    protected ?HooksAPIInterface $hooksAPI = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->instanceManager->getInstance(HooksAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractUserAvatarTypeAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
}
