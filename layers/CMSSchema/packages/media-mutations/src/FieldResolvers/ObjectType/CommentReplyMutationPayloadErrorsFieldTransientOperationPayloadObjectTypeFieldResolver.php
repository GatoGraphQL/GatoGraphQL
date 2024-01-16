<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentReplyMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver = null;

    final public function setCommentReplyMutationErrorPayloadUnionTypeResolver(CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver): void
    {
        $this->commentReplyMutationErrorPayloadUnionTypeResolver = $commentReplyMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCommentReplyMutationErrorPayloadUnionTypeResolver(): CommentReplyMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentReplyMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentReplyMutationErrorPayloadUnionTypeResolver */
            $commentReplyMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeResolver::class);
            $this->commentReplyMutationErrorPayloadUnionTypeResolver = $commentReplyMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentReplyMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentReplyMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeResolver();
    }
}
