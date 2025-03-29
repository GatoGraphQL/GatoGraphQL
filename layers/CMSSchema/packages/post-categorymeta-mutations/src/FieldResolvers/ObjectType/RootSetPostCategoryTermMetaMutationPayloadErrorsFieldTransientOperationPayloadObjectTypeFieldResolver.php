<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootSetPostCategoryTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootSetPostCategoryTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
