<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\ConditionalOnContext\PluginOwnUse\SchemaServices\FieldResolvers\ObjectType;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

/**
 * These fields must be accessed by the plugin only,
 * they are unavailable otherwise (even to the admin
 * user in the wp-admin GraphiQL client).
 */
abstract class AbstractForPluginOwnUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractListOfCPTEntitiesRootObjectTypeFieldResolver
{
    private ?UserAuthorizationInterface $userAuthorization = null;

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    public function resolveCanProcessField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        if (
            !parent::resolveCanProcessField(
                $objectTypeResolver,
                $field,
            )
        ) {
            return false;
        }
        return $this->getUserAuthorization()->canAccessSchemaEditor();
    }
}
