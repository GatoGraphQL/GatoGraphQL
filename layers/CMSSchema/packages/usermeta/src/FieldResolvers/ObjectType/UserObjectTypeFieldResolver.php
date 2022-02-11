<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

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
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
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
