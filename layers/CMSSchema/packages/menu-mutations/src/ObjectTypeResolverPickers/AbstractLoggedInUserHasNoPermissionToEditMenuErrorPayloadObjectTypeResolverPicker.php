<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\LoggedInUserHasNoPermissionToEditMenuErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditMenuErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditMenuErrorPayload::class;
    }
}
