<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\UserHasNoPermissionToUploadFilesErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = null;

    final public function setUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver): void
    {
        $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
    }
    final protected function getUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver(): UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver
    {
        if ($this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver */
            $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver::class);
            $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver = $commentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
        }
        return $this->commentAuthorNameIsMissingErrorPayloadObjectTypeResolver;
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
