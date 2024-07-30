<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditCustomPostErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit the requested taxonomy"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
