<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentAddMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCommentAddMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentAddMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCommentAddMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCommentAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
