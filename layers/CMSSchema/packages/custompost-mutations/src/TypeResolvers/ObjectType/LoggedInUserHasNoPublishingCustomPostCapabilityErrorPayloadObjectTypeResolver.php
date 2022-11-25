<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
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
        /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit custom posts"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
