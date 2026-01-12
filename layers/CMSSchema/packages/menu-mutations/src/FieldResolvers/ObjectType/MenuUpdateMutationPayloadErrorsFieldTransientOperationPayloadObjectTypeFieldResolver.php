<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\MenuUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\MenuUpdateMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MenuUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?MenuUpdateMutationErrorPayloadUnionTypeResolver $menuUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getMenuUpdateMutationErrorPayloadUnionTypeResolver(): MenuUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->menuUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var MenuUpdateMutationErrorPayloadUnionTypeResolver */
            $menuUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(MenuUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->menuUpdateMutationErrorPayloadUnionTypeResolver = $menuUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->menuUpdateMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getMenuUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
