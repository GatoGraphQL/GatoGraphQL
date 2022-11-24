<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostUpdateMutationErrorPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
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

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
