<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootUpdatePageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootUpdatePageMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdatePageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdatePageMutationErrorPayloadUnionTypeResolver $rootUpdatePageMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdatePageMutationErrorPayloadUnionTypeResolver(RootUpdatePageMutationErrorPayloadUnionTypeResolver $rootUpdatePageMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver = $rootUpdatePageMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdatePageMutationErrorPayloadUnionTypeResolver(): RootUpdatePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePageMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver = $rootUpdatePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdatePageMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePageMutationErrorPayloadUnionTypeResolver();
    }
}
