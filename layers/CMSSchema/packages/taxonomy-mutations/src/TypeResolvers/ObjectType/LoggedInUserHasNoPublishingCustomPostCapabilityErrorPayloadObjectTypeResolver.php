<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to publish taxonomys"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
