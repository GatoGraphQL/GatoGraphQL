<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMeta\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user = $object;
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getUserMetaTypeAPI()->getUserMeta(
                    $objectTypeResolver->getID($user),
                    $field->getArgument('key')?->getValue(),
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}
