<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
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
        /** @var LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader */
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditCustomPostErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit the requested custom post"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
