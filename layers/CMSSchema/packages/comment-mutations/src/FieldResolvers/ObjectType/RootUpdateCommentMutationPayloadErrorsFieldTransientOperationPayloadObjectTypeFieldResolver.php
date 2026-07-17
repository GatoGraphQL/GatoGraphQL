<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootUpdateCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootUpdateCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateCommentMutationErrorPayloadUnionTypeResolver $rootUpdateCommentMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateCommentMutationErrorPayloadUnionTypeResolver(): RootUpdateCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateCommentMutationErrorPayloadUnionTypeResolver */
            $rootUpdateCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver = $rootUpdateCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateCommentMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateCommentMutationErrorPayloadUnionTypeResolver();
    }
}
