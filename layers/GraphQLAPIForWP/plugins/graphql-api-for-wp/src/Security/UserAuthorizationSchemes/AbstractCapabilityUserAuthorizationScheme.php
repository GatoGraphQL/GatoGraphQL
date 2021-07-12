<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractCapabilityUserAuthorizationScheme extends AbstractAutomaticallyInstantiatedService implements CapabilityUserAuthorizationSchemeInterface
{
    public function getName(): string
    {
        return sprintf(
            'capability-%s',
            $this->getSchemaEditorAccessCapability()
        );
    }

    public function canAccessSchemaEditor(): bool
    {
        return \current_user_can($this->getSchemaEditorAccessCapability());
    }
}
