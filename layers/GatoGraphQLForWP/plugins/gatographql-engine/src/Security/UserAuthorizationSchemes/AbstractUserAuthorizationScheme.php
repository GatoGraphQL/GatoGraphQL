<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractUserAuthorizationScheme extends AbstractAutomaticallyInstantiatedService implements UserAuthorizationSchemeInterface
{
    public function getPriority(): int
    {
        return 10;
    }
}
