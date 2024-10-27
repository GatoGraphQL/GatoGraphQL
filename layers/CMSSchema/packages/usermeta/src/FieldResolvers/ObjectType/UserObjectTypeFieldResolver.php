<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?UserMetaTypeAPIInterface $userMetaTypeAPI = null;

    final protected function getUserMetaTypeAPI(): UserMetaTypeAPIInterface
    {
        if ($this->userMetaTypeAPI === null) {
            /** @var UserMetaTypeAPIInterface */
            $userMetaTypeAPI = $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
            $this->userMetaTypeAPI = $userMetaTypeAPI;
        }
        return $this->userMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getUserMetaTypeAPI()->getUserMeta(
                    $user,
                    $fieldDataAccessor->getValue('key'),
                    $fieldDataAccessor->getFieldName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
