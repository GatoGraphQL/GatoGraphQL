<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\RootSetCategoriesOnCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetCategoriesOnCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver(RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver(): RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetCategoriesOnCustomPostMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
