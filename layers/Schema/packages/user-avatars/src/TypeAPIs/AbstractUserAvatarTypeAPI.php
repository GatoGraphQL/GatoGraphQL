<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI
    ) {
    }
}
