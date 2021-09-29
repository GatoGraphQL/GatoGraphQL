<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;
use Symfony\Contracts\Service\Attribute\Required;

class ValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveRolePublicSchemaRelationalTypeResolverDecoratorTrait;

    protected ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;

    #[Required]
    public function autowireValidateDoesLoggedInUserHaveRoleForDirectivesPublicSchemaRelationalTypeResolverDecorator(
        ValidateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver,
    ): void {
        $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::ROLES);
    }

    protected function getValidateRoleDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->validateDoesLoggedInUserHaveAnyRoleForDirectivesDirectiveResolver;
    }
}
