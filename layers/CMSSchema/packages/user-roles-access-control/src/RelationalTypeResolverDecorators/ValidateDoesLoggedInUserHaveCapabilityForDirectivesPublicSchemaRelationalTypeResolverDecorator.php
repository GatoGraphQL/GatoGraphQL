<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver = null;

    final public function setValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }

    protected function getValidateCapabilityFieldDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesFieldDirectiveResolver();
    }
}
