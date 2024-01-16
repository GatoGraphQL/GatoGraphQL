<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostAddCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(): CustomPostAddCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->customPostAddCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeResolver */
            $customPostAddCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostAddCommentMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver();
    }
}
