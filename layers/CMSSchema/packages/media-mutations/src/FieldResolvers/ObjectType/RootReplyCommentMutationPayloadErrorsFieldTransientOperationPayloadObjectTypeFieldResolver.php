<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootReplyCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootReplyCommentMutationErrorPayloadUnionTypeResolver(RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootReplyCommentMutationErrorPayloadUnionTypeResolver(): RootReplyCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootReplyCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootReplyCommentMutationErrorPayloadUnionTypeResolver */
            $rootReplyCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootReplyCommentMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeResolver();
    }
}
