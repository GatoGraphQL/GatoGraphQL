<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    public function autowireAbstractUserAvatarTypeAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
}
