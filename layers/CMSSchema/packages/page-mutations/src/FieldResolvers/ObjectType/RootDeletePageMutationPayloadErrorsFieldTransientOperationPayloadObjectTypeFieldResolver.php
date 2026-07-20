<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootDeletePageMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\RootDeletePageMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeletePageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeletePageMutationErrorPayloadUnionTypeResolver $rootDeletePageMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePageMutationErrorPayloadUnionTypeResolver(): RootDeletePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePageMutationErrorPayloadUnionTypeResolver */
            $rootDeletePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePageMutationErrorPayloadUnionTypeResolver = $rootDeletePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePageMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeletePageMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeletePageMutationErrorPayloadUnionTypeResolver();
    }
}
