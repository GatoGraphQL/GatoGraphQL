<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddCommentToCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(): RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
