<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootAddGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddGenericCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
