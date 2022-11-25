<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
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
        /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver */
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver::class);
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
