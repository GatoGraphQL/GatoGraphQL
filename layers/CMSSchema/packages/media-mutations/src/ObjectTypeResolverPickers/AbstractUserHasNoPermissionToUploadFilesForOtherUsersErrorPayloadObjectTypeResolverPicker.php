<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver = null;

    final public function setUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver(UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver): void
    {
        $this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver = $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver;
    }
    final protected function getUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver(): UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver
    {
        if ($this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver */
            $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver::class);
            $this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver = $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver;
        }
        return $this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload::class;
    }
}
