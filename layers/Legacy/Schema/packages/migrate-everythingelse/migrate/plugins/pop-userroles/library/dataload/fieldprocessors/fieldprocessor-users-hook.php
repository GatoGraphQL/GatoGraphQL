<?php
namespace PoPSchema\UserRoles;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'role',
            'hasRole',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'role' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasRole' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'hasRole',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'role' => $translationAPI->__('', ''),
            'hasRole' => $translationAPI->__('', ''),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'hasRole':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'role',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The role name to compare against', 'user-roles'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $user = $object;
        switch ($fieldName) {
            case 'role':
                $user_roles = $userRoleTypeAPI->getUserRoles($objectTypeResolver->getID($user));
                // Allow to hook for URE: Make sure we always get the most specific role
                // Otherwise, users like Leo get role 'administrator'
                return HooksAPIFacade::getInstance()->applyFilters(
                    'UserObjectTypeResolver:getValue:role',
                    array_shift($user_roles),
                    $objectTypeResolver->getID($user)
                );
            case 'hasRole':
                $role = $objectTypeResolver->resolveValue($user, 'role', $variables, $expressions, $options);
                if (GeneralUtils::isError($role)) {
                    return $role;
                }
                return $role == $fieldArgs['role'];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
