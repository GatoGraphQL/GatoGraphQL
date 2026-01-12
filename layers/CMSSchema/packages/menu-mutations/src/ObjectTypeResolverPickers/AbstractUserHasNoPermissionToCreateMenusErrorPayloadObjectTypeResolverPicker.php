<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\UserHasNoPermissionToCreateMenusErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver = null;

    final protected function getUserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver(): UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver
    {
        if ($this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver === null) {
            /** @var UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver */
            $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver::class);
            $this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver = $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver;
        }
        return $this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserHasNoPermissionToCreateMenusErrorPayload::class;
    }
}
