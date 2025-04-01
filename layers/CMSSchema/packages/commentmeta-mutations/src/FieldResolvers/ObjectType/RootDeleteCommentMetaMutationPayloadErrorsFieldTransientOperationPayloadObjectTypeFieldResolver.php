<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootDeleteCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
