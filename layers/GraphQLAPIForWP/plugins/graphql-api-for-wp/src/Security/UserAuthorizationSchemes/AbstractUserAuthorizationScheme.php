<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractUserAuthorizationScheme extends AbstractAutomaticallyInstantiatedService implements UserAuthorizationSchemeInterface
{
    public function getName(): string
    {
        return sprintf(
            'access_scheme-%s',
            $this->getSchemaEditorAccessCapability()
        );
    }
}
