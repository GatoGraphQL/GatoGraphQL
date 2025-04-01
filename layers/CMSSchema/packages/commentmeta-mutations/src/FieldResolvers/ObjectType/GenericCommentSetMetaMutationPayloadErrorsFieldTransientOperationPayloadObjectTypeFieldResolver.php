<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\GenericCommentSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCommentSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentSetMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCommentSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getGenericCommentSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
