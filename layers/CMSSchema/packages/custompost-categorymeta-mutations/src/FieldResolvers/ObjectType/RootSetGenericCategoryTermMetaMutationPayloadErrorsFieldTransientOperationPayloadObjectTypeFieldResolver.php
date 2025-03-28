<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetGenericCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
