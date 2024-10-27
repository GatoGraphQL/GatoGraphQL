<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoPublishingPageCapabilityErrorPayload;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPublishingPageCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPublishingPageCapabilityErrorPayload::class;
    }
}
