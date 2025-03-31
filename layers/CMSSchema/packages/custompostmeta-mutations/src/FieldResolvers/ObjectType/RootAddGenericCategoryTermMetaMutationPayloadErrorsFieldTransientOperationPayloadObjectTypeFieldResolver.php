<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddGenericCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
