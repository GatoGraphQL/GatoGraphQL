<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait RolesObjectTypeFieldResolverTrait
{
    protected UserRoleObjectTypeResolver $userRoleObjectTypeResolver;

    #[Required]
    public function autowireUserRolesWPRolesObjectTypeFieldResolverTrait(
        UserRoleObjectTypeResolver $userRoleObjectTypeResolver,
    ): void {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'roles' => $this->userRoleObjectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
