<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\CommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentUpdateMetaMutationErrorPayloadUnionTypeResolver $commentUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentUpdateMetaMutationErrorPayloadUnionTypeResolver(): CommentUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $commentUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver = $commentUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
