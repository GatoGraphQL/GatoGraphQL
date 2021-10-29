<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeAPIs;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserAvatarTypeAPI implements UserAvatarTypeAPIInterface
{
    use WithHooksAPIServiceTrait;
}
