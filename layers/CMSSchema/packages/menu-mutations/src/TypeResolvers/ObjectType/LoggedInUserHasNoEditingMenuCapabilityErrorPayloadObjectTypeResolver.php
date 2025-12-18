<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingMenuCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user cannot edit menus"', 'menu-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
