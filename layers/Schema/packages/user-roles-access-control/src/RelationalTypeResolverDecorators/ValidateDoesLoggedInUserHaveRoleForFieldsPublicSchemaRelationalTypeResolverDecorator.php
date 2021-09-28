<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\RelationalTypeResolverDecorators\ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver;

class ValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaRelationalTypeResolverDecorator extends AbstractPublicSchemaRelationalTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsRelationalTypeResolverDecoratorTrait;
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    protected AccessControlManagerInterface $accessControlManager;
    protected ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver;

    #[Required]
    public function autowireValidateDoesLoggedInUserHaveRoleForFieldsPublicSchemaRelationalTypeResolverDecorator(
        AccessControlManagerInterface $accessControlManager,
        ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver,
    ): void {
        $this->accessControlManager = $accessControlManager;
        $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForFields(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleDirectiveResolver;
    }
}
