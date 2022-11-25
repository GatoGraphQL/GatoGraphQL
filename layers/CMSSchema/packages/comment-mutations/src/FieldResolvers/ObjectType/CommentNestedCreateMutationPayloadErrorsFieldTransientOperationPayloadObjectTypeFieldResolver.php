<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentNestedCreateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentNestedCreateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentNestedCreateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentNestedCreateMutationErrorPayloadUnionTypeResolver $commentNestedCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCommentNestedCreateMutationErrorPayloadUnionTypeResolver(CommentNestedCreateMutationErrorPayloadUnionTypeResolver $commentNestedCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->commentNestedCreateMutationErrorPayloadUnionTypeResolver = $commentNestedCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCommentNestedCreateMutationErrorPayloadUnionTypeResolver(): CommentNestedCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentNestedCreateMutationErrorPayloadUnionTypeResolver */
        return $this->commentNestedCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CommentNestedCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentNestedCreateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentNestedCreateMutationErrorPayloadUnionTypeResolver();
    }
}
