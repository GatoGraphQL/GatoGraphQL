<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreateGenericCategoryMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver(RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCategoryMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
