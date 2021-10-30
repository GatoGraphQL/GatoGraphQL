<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
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
    private ?UserAvatarTypeAPIInterface $userAvatarTypeAPI = null;
    private ?UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry = null;
    private ?UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setUserAvatarTypeAPI(UserAvatarTypeAPIInterface $userAvatarTypeAPI): void
    {
        $this->userAvatarTypeAPI = $userAvatarTypeAPI;
    }
    final protected function getUserAvatarTypeAPI(): UserAvatarTypeAPIInterface
    {
        return $this->userAvatarTypeAPI ??= $this->instanceManager->getInstance(UserAvatarTypeAPIInterface::class);
    }
    final public function setUserAvatarRuntimeRegistry(UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry): void
    {
        $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
    }
    final protected function getUserAvatarRuntimeRegistry(): UserAvatarRuntimeRegistryInterface
    {
        return $this->userAvatarRuntimeRegistry ??= $this->instanceManager->getInstance(UserAvatarRuntimeRegistryInterface::class);
    }
    final public function setUserAvatarObjectTypeResolver(UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver): void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    final protected function getUserAvatarObjectTypeResolver(): UserAvatarObjectTypeResolver
    {
        return $this->userAvatarObjectTypeResolver ??= $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'avatar' => $this->getTranslationAPI()->__('User avatar', 'user-avatars'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'avatar' => [
                'size' => $this->getIntScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['avatar' => 'size'] => $this->getTranslationAPI()->__('Avatar size', 'user-avatars'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            ['avatar' => 'size'] => ComponentConfiguration::getUserAvatarDefaultSize(),
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
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
        $user = $object;
        switch ($fieldName) {
            case 'avatar':
                // Create the avatar, and store it in the dynamic registry
                $avatarSize = $fieldArgs['size'] ?? ComponentConfiguration::getUserAvatarDefaultSize();
                $avatarSrc = $this->getUserAvatarTypeAPI()->getUserAvatarSrc($user, $avatarSize);
                if ($avatarSrc === null) {
                    return null;
                }
                $avatarIDComponents = [
                    'src' => $avatarSrc,
                    'size' => $avatarSize,
                ];
                // Generate a hash to represent the ID of the avatar given its properties
                $avatarID = hash('md5', json_encode($avatarIDComponents));
                $this->getUserAvatarRuntimeRegistry()->storeUserAvatar(new UserAvatar($avatarID, $avatarSrc, $avatarSize));
                return $avatarID;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'avatar' => $this->getUserAvatarObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
