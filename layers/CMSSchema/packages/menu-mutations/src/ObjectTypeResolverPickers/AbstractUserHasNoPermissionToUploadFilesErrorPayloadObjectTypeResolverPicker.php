<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\UserHasNoPermissionToUploadFilesErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver $userHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver = null;

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
