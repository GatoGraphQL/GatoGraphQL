<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\UserHasNoPermissionToCreateMenusForOtherUsersErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver = null;

    final protected function getUserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver(): UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver
    {
        if ($this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver */
            $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver::class);
            $this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver = $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver;
        }
        return $this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserHasNoPermissionToCreateMenusForOtherUsersErrorPayload::class;
    }
}
