<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }

    protected function getValidateCapabilityDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver();
    }
}
