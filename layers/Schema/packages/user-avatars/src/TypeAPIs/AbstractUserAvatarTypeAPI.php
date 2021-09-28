<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireAbstractUserAvatarTypeAPI(HooksAPIInterface $hooksAPI)
    {
        $this->hooksAPI = $hooksAPI;
    }
}
