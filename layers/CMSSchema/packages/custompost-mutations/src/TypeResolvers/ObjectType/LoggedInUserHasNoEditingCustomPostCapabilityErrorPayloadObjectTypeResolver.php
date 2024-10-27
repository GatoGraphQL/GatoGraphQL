<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
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
