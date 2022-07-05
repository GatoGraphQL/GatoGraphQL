<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

class CustomPostObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;

    final public function setCustomPostMetaTypeAPI(CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI): void
    {
        $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
    }
    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        return $this->customPostMetaTypeAPI ??= $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPost = $object;
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                    $objectTypeResolver->getID($customPost),
                    $fieldDataAccessor->getValue('key'),
                    $field->getName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
