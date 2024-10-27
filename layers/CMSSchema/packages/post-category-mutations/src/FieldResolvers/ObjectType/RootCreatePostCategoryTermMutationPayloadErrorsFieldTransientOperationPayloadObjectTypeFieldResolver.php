<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\RootCreatePostCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreatePostCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver(): RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreatePostCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
