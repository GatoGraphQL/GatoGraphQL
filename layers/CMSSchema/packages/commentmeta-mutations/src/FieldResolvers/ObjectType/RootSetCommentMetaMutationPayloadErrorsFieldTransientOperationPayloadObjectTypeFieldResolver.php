<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootSetCommentMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetCommentMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetCommentMetaMutationErrorPayloadUnionTypeResolver $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetCommentMetaMutationErrorPayloadUnionTypeResolver(): RootSetCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetCommentMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
