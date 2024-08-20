<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\LoggedInUserHasNoPermissionToEditMediaItemErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditMediaItemMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver(LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditMediaItemErrorPayload::class;
    }
}
