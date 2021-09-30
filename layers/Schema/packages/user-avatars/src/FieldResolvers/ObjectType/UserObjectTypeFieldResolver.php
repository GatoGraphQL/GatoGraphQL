<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\UserAvatars\ComponentConfiguration;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;
use PoPSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface;
use PoPSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected UserAvatarTypeAPIInterface $userAvatarTypeAPI;
    protected UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry;
    protected UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;

    #[Required]
    public function autowireUserObjectTypeFieldResolver(
        UserAvatarTypeAPIInterface $userAvatarTypeAPI,
        UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry,
        UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
    ): void {
        $this->userAvatarTypeAPI = $userAvatarTypeAPI;
        $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'avatar',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'avatar' => $this->translationAPI->__('User avatar', 'user-avatars'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'avatar':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'size',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Avatar size', 'user-avatars'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => ComponentConfiguration::getUserAvatarDefaultSize(),
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
        $user = $object;
        switch ($fieldName) {
            case 'avatar':
                // Create the avatar, and store it in the dynamic registry
                $avatarSize = $fieldArgs['size'] ?? ComponentConfiguration::getUserAvatarDefaultSize();
                $avatarSrc = $this->userAvatarTypeAPI->getUserAvatarSrc($user, $avatarSize);
                if ($avatarSrc === null) {
                    return null;
                }
                $avatarIDComponents = [
                    'src' => $avatarSrc,
                    'size' => $avatarSize,
                ];
                // Generate a hash to represent the ID of the avatar given its properties
                $avatarID = hash('md5', json_encode($avatarIDComponents));
                $this->userAvatarRuntimeRegistry->storeUserAvatar(new UserAvatar($avatarID, $avatarSrc, $avatarSize));
                return $avatarID;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'avatar':
                return $this->userAvatarObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
