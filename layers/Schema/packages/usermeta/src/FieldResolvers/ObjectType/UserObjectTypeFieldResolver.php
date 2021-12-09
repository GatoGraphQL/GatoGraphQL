<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;

    final public function setUserMetaTypeAPI(UserMetaTypeAPIInterface $userMetaTypeAPI): void
    {
        $this->userMetaTypeAPI = $userMetaTypeAPI;
    }
    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        return $this->userMetaTypeAPI ??= $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getUserMetaTypeAPI();
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
                return $this->getUserMetaTypeAPI()->getUserMeta(
                    $objectTypeResolver->getID($user),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
