<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateGenericCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
