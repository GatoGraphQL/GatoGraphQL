<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreateGenericCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver(RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
