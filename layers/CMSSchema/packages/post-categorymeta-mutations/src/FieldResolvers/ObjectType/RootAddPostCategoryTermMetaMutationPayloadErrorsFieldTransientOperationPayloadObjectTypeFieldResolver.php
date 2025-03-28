<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootAddPostCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddPostCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
