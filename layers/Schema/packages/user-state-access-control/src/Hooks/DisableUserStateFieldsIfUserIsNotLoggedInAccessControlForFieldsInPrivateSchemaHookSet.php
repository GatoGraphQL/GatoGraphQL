<?php

declare(strict_types=1);

namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectTypeFieldResolverInterface;
use PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet;

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
        RelationalTypeResolverInterface $relationalTypeResolver,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        array $interfaceTypeResolverClasses,
        string $fieldName
    ): bool {
        return $objectTypeFieldResolver instanceof AbstractUserStateFieldResolver;
    }
}
