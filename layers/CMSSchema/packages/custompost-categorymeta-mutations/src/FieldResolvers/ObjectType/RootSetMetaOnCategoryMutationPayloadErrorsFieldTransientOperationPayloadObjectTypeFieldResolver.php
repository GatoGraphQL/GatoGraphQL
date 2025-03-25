<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetMetaOnCategoryMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetMetaOnCategoryMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver(): RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver */
            $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetMetaOnCategoryMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
