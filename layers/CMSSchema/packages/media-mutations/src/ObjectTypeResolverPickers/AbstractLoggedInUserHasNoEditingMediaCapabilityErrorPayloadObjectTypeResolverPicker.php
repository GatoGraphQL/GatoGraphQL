<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\LoggedInUserHasNoEditingMediaCapabilityErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingMediaCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingMediaCapabilityErrorPayload::class;
    }
}
