<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\MenuDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\MenuDeleteMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MenuDeleteMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?MenuDeleteMutationErrorPayloadUnionTypeResolver $menuDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getMenuDeleteMutationErrorPayloadUnionTypeResolver(): MenuDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->menuDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var MenuDeleteMutationErrorPayloadUnionTypeResolver */
            $menuDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(MenuDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->menuDeleteMutationErrorPayloadUnionTypeResolver = $menuDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->menuDeleteMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuDeleteMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getMenuDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
