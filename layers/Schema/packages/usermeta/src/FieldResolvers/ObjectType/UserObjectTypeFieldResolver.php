<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\FieldResolvers\ObjectType;

use InvalidArgumentException;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;
    private ?WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver = null;

    final public function setUserMetaTypeAPI(UserMetaTypeAPIInterface $userMetaTypeAPI): void
    {
        $this->userMetaTypeAPI = $userMetaTypeAPI;
    }
    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        return $this->userMetaTypeAPI ??= $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
    }
    final public function setWithMetaInterfaceTypeFieldResolver(WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver): void
    {
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }
    final protected function getWithMetaInterfaceTypeFieldResolver(): WithMetaInterfaceTypeFieldResolver
    {
        return $this->withMetaInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getWithMetaInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $user = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                try {
                    $value = $this->getUserMetaTypeAPI()->getUserMeta(
                        $objectTypeResolver->getID($user),
                        $fieldArgs['key'],
                        $fieldName === 'metaValue'
                    );
                } catch (InvalidArgumentException $e) {
                    // If the meta key is not in the allowlist, it will throw an exception
                    return new Error(
                        'meta-key-not-exists',
                        $e->getMessage()
                    );
                }
                return $value;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
