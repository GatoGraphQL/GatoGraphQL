<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver(RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
