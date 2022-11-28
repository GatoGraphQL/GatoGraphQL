<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver(CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostNestedUpdateMutationErrorPayloadUnionTypeResolver = $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver(): CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostNestedUpdateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver::class);
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
        return $this->getCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
