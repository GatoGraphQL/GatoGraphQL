<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentUpdateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentUpdateMutationErrorPayloadUnionTypeResolver $commentUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentUpdateMutationErrorPayloadUnionTypeResolver(): CommentUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentUpdateMutationErrorPayloadUnionTypeResolver */
            $commentUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->commentUpdateMutationErrorPayloadUnionTypeResolver = $commentUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
