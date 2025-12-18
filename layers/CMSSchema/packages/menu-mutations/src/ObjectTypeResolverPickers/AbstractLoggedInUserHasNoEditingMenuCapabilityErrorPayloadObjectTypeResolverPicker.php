<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\LoggedInUserHasNoEditingMenuCapabilityErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingMenuCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingMenuCapabilityErrorPayload::class;
    }
}
