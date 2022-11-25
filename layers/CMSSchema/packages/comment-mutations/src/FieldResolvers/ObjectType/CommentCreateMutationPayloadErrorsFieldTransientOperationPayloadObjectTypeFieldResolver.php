<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentCreateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentCreateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentCreateMutationErrorPayloadUnionTypeResolver $commentCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCommentCreateMutationErrorPayloadUnionTypeResolver(CommentCreateMutationErrorPayloadUnionTypeResolver $commentCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->commentCreateMutationErrorPayloadUnionTypeResolver = $commentCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCommentCreateMutationErrorPayloadUnionTypeResolver(): CommentCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentCreateMutationErrorPayloadUnionTypeResolver */
        return $this->commentCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CommentCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentCreateMutationErrorPayloadUnionTypeResolver();
    }
}
