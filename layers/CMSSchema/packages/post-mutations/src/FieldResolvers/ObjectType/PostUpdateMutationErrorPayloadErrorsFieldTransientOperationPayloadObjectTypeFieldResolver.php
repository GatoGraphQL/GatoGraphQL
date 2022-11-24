<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostUpdateMutationErrorPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CustomPostUpdateMutationErrorPayloadUnionTypeResolver $customPostUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostUpdateMutationErrorPayloadUnionTypeResolver(CustomPostUpdateMutationErrorPayloadUnionTypeResolver $customPostUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostUpdateMutationErrorPayloadUnionTypeResolver = $customPostUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostUpdateMutationErrorPayloadUnionTypeResolver(): CustomPostUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostUpdateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUpdateMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
