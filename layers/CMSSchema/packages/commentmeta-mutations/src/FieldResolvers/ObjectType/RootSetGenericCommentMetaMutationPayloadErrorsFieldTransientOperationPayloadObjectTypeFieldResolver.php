<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootSetGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetGenericCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetGenericCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
