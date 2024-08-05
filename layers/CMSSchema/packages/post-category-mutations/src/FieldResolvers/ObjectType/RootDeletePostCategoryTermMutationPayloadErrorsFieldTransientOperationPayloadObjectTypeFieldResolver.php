<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\RootDeletePostCategoryTermMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePostCategoryTermMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver(RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver(): RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePostCategoryTermMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
