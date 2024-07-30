<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCategoryMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCategoryMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
