<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    public function autowireAbstractUserAvatarTypeAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
}
