<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\MenuUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootCreateMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\RootUpdateMenuMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class MenuMutationPayloadObjectTypeFieldResolver extends AbstractObjectMutationPayloadObjectTypeFieldResolver
{
    private ?MenuObjectTypeResolver $menuObjectTypeResolver = null;

    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        if ($this->menuObjectTypeResolver === null) {
            /** @var MenuObjectTypeResolver */
            $menuObjectTypeResolver = $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
            $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        }
        return $this->menuObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootCreateMenuMutationPayloadObjectTypeResolver::class,
            RootUpdateMenuMutationPayloadObjectTypeResolver::class,
            MenuUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getObjectFieldName(): string
    {
        return 'menu';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectFieldName() => $this->getMenuObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
