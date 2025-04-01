<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
