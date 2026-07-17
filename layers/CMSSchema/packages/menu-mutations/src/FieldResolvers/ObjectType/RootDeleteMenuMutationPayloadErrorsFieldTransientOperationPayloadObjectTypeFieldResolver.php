<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootDeleteMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\RootDeleteMenuMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootDeleteMenuMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootDeleteMenuMutationErrorPayloadUnionTypeResolver $rootDeleteMenuMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteMenuMutationErrorPayloadUnionTypeResolver(): RootDeleteMenuMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteMenuMutationErrorPayloadUnionTypeResolver */
            $rootDeleteMenuMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteMenuMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver = $rootDeleteMenuMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootDeleteMenuMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootDeleteMenuMutationErrorPayloadUnionTypeResolver();
    }
}
