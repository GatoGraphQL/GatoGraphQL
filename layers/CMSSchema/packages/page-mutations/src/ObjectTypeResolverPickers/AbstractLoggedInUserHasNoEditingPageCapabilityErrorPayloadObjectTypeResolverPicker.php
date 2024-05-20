<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoEditingPageCapabilityErrorPayload;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingPageCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingPageCapabilityErrorPayload::class;
    }
}
