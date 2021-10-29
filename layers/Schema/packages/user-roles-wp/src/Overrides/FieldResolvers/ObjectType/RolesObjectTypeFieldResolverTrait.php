<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait RolesObjectTypeFieldResolverTrait
{
    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;

    public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        return $this->userRoleObjectTypeResolver ??= $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'roles' => $this->getUserRoleObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
