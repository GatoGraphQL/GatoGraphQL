<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostNestedUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostNestedUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
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
            PostNestedUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
