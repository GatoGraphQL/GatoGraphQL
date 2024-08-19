<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToEditMediaItemErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToEditMediaItemMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = null;

    final public function setUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver(UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver): void
    {
        $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
    }
    final protected function getUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver(): UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver
    {
        if ($this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver */
            $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver::class);
            $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver = $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
        }
        return $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserHasNoPermissionToEditMediaItemErrorPayload::class;
    }
}
