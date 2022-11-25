<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver
    {
        /** @var LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver */
        return $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload::class;
    }
}
