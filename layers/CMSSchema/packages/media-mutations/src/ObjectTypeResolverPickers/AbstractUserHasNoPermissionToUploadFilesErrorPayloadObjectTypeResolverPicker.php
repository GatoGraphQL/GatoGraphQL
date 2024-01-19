<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver = null;

    final public function setUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver): void
    {
        $this->userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver = $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
    }
    final protected function getUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver(): UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver
    {
        if ($this->userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver */
            $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver::class);
            $this->userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver = $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
        }
        return $this->userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserHasNoPermissionToUploadFilesErrorPayload::class;
    }
}
