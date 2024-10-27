<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\RootUpdatePostCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver(): RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePostCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
