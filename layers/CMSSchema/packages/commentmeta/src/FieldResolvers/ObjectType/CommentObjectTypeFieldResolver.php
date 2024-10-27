<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

class CommentObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    private ?CommentMetaTypeAPIInterface $commentMetaTypeAPI = null;

    final protected function getCommentMetaTypeAPI(): CommentMetaTypeAPIInterface
    {
        if ($this->commentMetaTypeAPI === null) {
            /** @var CommentMetaTypeAPIInterface */
            $commentMetaTypeAPI = $this->instanceManager->getInstance(CommentMetaTypeAPIInterface::class);
            $this->commentMetaTypeAPI = $commentMetaTypeAPI;
        }
        return $this->commentMetaTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentObjectTypeResolver::class,
        ];
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCommentMetaTypeAPI();
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $comment = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCommentMetaTypeAPI()->getCommentMeta(
                    $comment,
                    $fieldDataAccessor->getValue('key'),
                    $fieldDataAccessor->getFieldName() === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
