<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

class CustomPostObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;

    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        if ($this->customPostMetaTypeAPI === null) {
            /** @var CustomPostMetaTypeAPIInterface */
            $customPostMetaTypeAPI = $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
            $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
        }
        return $this->customPostMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
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
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta(
                    $customPost,
                    $fieldDataAccessor->getValue('key'),
                    $fieldDataAccessor->getFieldName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
