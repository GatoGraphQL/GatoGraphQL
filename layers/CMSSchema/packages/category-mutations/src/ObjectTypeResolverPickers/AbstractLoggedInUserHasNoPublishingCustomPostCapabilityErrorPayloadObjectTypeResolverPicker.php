<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload::class;
    }
}
