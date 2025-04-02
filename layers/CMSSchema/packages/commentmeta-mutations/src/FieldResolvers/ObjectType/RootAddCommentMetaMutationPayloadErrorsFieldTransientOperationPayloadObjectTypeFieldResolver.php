<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootAddCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootAddCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddCommentMetaMutationErrorPayloadUnionTypeResolver $rootAddCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddCommentMetaMutationErrorPayloadUnionTypeResolver(): RootAddCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddCommentMetaMutationErrorPayloadUnionTypeResolver = $rootAddCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
