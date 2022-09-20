<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\RelationalTypeResolverDecorators\ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoPCMSSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver;
use PoPCMSSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait;

    private ?AccessControlManagerInterface $accessControlManager = null;
    private ?ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        /** @var AccessControlManagerInterface */
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }
    final public function setValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver(ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver): void
    {
        $this->validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver;
    }
    final protected function getValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver(): ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver
    {
        /** @var ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver */
        return $this->validateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver ??= $this->instanceManager->getInstance(ValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver::class);
    }

    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries(): array
    {
        return $this->getAccessControlManager()->getEntriesForFields(AccessControlGroups::CAPABILITIES);
    }

    protected function getValidateCapabilityFieldDirectiveResolver(): FieldDirectiveResolverInterface
    {
        return $this->getValidateDoesLoggedInUserHaveAnyCapabilityFieldDirectiveResolver();
    }
}
