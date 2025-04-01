<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
