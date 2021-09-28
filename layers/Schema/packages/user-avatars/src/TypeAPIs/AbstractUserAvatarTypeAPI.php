<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    protected HooksAPIInterface $hooksAPI;
    public function __construct(HooksAPIInterface $hooksAPI)
    {
        $this->hooksAPI = $hooksAPI;
    }
}
