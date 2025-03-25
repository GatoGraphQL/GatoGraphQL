<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootCreateGenericCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateGenericCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
