<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver extends LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditPageErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit the requested page"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeDataLoader();
    }
}
