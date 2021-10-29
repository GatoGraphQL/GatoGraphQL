<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class RootRolesObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;

    protected ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;

    public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        return $this->userRoleTypeAPI ??= $this->getInstanceManager()->getInstance(UserRoleTypeAPIInterface::class);
    }

    //#[Required]
    final public function autowireRootRolesObjectTypeFieldResolver(
        UserRoleTypeAPIInterface $userRoleTypeAPI,
    ): void {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'roles',
            'capabilities'
                => SchemaTypeModifiers::NON_NULLABLE
                | SchemaTypeModifiers::IS_ARRAY
                | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'roles':
                return $this->getUserRoleTypeAPI()->getRoleNames();
            case 'capabilities':
                return $this->getUserRoleTypeAPI()->getCapabilities();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
