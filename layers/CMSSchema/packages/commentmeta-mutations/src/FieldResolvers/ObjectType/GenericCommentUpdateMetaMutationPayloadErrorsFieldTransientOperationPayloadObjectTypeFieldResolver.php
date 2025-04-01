<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentUpdateMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCommentUpdateMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCommentUpdateMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
