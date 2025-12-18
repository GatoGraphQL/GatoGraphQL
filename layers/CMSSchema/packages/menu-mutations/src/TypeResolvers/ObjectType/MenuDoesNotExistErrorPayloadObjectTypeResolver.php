<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType\MenuDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MenuDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MenuDoesNotExistErrorPayloadObjectTypeDataLoader $menuDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getMenuDoesNotExistErrorPayloadObjectTypeDataLoader(): MenuDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->menuDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var MenuDoesNotExistErrorPayloadObjectTypeDataLoader */
            $menuDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MenuDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->menuDoesNotExistErrorPayloadObjectTypeDataLoader = $menuDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->menuDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MenuDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested menu does not exist"', 'custompostmenu-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
