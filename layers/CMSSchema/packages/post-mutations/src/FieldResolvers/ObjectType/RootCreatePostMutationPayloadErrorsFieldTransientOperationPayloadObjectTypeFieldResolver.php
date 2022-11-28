<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostCreateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CustomPostCreateMutationErrorPayloadUnionTypeResolver $customPostCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostCreateMutationErrorPayloadUnionTypeResolver(CustomPostCreateMutationErrorPayloadUnionTypeResolver $customPostCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostCreateMutationErrorPayloadUnionTypeResolver = $customPostCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostCreateMutationErrorPayloadUnionTypeResolver(): CustomPostCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostCreateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCustomPostCreateMutationErrorPayloadUnionTypeResolver();
    }
}
