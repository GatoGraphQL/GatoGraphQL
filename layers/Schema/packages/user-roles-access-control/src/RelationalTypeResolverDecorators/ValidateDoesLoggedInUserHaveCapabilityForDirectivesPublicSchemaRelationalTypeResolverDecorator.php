<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\RelationalTypeResolverDecorators;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\AccessControl\RelationalTypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoPSchema\UserRolesAccessControl\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups;

class ValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaRelationalTypeResolverDecorator extends AbstractConfigurableAccessControlForDirectivesInPublicSchemaRelationalTypeResolverDecorator
{
    use ValidateDoesLoggedInUserHaveCapabilityPublicSchemaRelationalTypeResolverDecoratorTrait;

    protected ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;

    #[Required]
    public function autowireValidateDoesLoggedInUserHaveCapabilityForDirectivesPublicSchemaRelationalTypeResolverDecorator(
        ValidateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver,
    ): void {
        $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver = $validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
    }

    protected function getConfigurationEntries(): array
    {
        return $this->accessControlManager->getEntriesForDirectives(AccessControlGroups::CAPABILITIES);
    }

    protected function getValidateCapabilityDirectiveResolver(): DirectiveResolverInterface
    {
        return $this->validateDoesLoggedInUserHaveAnyCapabilityForDirectivesDirectiveResolver;
    }
}
