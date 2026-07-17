<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentDeleteMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentDeleteMutationErrorPayloadUnionTypeResolver $commentDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentDeleteMutationErrorPayloadUnionTypeResolver(): CommentDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentDeleteMutationErrorPayloadUnionTypeResolver */
            $commentDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->commentDeleteMutationErrorPayloadUnionTypeResolver = $commentDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
