<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\RelationalTypeResolverDecorators\ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;
    private ?ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        /** @var AccessControlManagerInterface */
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleFieldDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyRoleFieldDirectiveResolver();
    }
}
