<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMeta\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMeta\Module;
use PoPCMSSchema\CommentMeta\ModuleConfiguration;
use PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

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

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatCommentMetaKeysAsSensitiveData()) {
            $sensitiveFieldArgNames[] = 'metaKeys';
        }
        return $sensitiveFieldArgNames;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $comment = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaKeys':
                $metaKeys = [];
                $commentMetaTypeAPI = $this->getCommentMetaTypeAPI();
                $allCommentMetaKeys = $commentMetaTypeAPI->getCommentMetaKeys($comment);
                foreach ($allCommentMetaKeys as $key) {
                    if (!$commentMetaTypeAPI->validateIsMetaKeyAllowed($key))  {
                        continue;
                    }
                    $metaKeys[] = $key;
                }
                return $metaKeys;
            case 'metaValue':
            case 'metaValues':
                return $this->getCommentMetaTypeAPI()->getCommentMeta(
                    $comment,
                    $fieldDataAccessor->getValue('key'),
                    $fieldDataAccessor->getFieldName() === 'metaValue'
                );
            case 'jsonMeta':
                $jsonMeta = [];
                $allMeta = $this->getCommentMetaTypeAPI()->getAllCommentMeta($comment);
                /** @var string[] */
                $keys = $fieldDataAccessor->getValue('keys');
                foreach ($keys as $key) {
                    if (!array_key_exists($key, $allMeta)) {
                        continue;
                    }
                    $jsonMeta[$key] = $allMeta[$key];
                }
                return (object) $jsonMeta;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
