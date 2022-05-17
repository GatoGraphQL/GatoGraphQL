<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\UserState\FieldResolvers\ObjectType\AbstractUserStateObjectTypeFieldResolver;

class DisableUserStateFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet extends AbstractAccessControlForFieldsHookSet
{
    /**
     * If the user is not logged in, and we are in private mode,
     * then remove the field
     */
    protected function enabled(): bool
    {
        $isUserLoggedIn = App::getState('is-user-logged-in');
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->usePrivateSchemaMode() && !$isUserLoggedIn;
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
     */
    protected function removeFieldName(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        return $objectTypeOrInterfaceTypeFieldResolver instanceof AbstractUserStateObjectTypeFieldResolver;
    }
}
