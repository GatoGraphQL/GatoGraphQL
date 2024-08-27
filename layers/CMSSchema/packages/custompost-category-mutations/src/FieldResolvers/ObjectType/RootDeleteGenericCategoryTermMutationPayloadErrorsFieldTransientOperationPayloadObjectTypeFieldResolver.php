<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteGenericCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteGenericCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
