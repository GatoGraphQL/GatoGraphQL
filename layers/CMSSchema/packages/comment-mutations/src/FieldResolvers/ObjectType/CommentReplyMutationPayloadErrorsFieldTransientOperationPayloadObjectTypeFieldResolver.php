<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentReplyMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver = null;

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
