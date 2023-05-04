<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractUserAuthorizationScheme extends AbstractAutomaticallyInstantiatedService implements UserAuthorizationSchemeInterface
{
    public function getPriority(): int
    {
        return 10;
    }
}
