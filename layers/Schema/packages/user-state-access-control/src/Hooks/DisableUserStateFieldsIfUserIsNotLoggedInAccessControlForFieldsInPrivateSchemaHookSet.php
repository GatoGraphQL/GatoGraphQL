<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;

class DisableUserStateFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet extends AbstractAccessControlForFieldsHookSet
{
    /**
     * If the user is not logged in, and we are in private mode,
     * then remove the field
     */
    protected function enabled(): bool
    {
        $vars = ApplicationState::getVars();
        $isUserLoggedIn = $vars['global-userstate']['is-user-logged-in'];
        return ComponentConfiguration::usePrivateSchemaMode() && !$isUserLoggedIn;
    }

    /**
     * Apply to all fields
     */
    protected function getFieldNames(): array
    {
        return [];
    }
    /**
     * Remove the fieldNames if the fieldResolver is an instance of the "user state" one
     *
     * @param string[] $interfaceTypeResolverClasses
     */
    protected function removeFieldName(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): bool {
        return $objectTypeOrInterfaceTypeFieldResolver instanceof AbstractUserStateFieldResolver;
    }
}
