<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePostTermMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePostTermMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
