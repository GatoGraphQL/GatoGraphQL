<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentDeleteMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCommentDeleteMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCommentDeleteMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
