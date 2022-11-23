<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
    {
        /** @var LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit custom posts"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
