<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootUpdateMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\RootUpdateMenuMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootUpdateMenuMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?RootUpdateMenuMutationErrorPayloadUnionTypeResolver $rootUpdateMenuMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateMenuMutationErrorPayloadUnionTypeResolver(): RootUpdateMenuMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateMenuMutationErrorPayloadUnionTypeResolver */
            $rootUpdateMenuMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateMenuMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver = $rootUpdateMenuMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootUpdateMenuMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getRootUpdateMenuMutationErrorPayloadUnionTypeResolver();
    }
}
