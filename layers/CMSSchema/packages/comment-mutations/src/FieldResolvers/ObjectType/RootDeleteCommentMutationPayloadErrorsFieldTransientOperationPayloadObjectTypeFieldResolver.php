<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootDeleteCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootDeleteCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteCommentMutationErrorPayloadUnionTypeResolver $rootDeleteCommentMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteCommentMutationErrorPayloadUnionTypeResolver(): RootDeleteCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteCommentMutationErrorPayloadUnionTypeResolver */
            $rootDeleteCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver = $rootDeleteCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteCommentMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteCommentMutationErrorPayloadUnionTypeResolver();
    }
}
