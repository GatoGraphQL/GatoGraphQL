<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootUpdatePostMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePostMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
