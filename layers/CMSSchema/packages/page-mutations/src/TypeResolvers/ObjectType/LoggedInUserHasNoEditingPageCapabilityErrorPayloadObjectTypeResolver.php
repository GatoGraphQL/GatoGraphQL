<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingPageCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit pages"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
