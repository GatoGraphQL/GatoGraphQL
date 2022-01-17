<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\RelationalTypeResolverDecorators\ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;
    private ?ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver::class);
    }

    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver();
    }
}
