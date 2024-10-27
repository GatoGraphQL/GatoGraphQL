<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsLoggedInErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsLoggedInErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserIsLoggedInErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserIsLoggedInErrorPayloadObjectTypeResolver $userIsLoggedInErrorPayloadObjectTypeResolver = null;

    final protected function getUserIsLoggedInErrorPayloadObjectTypeResolver(): UserIsLoggedInErrorPayloadObjectTypeResolver
    {
        if ($this->userIsLoggedInErrorPayloadObjectTypeResolver === null) {
            /** @var UserIsLoggedInErrorPayloadObjectTypeResolver */
            $userIsLoggedInErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserIsLoggedInErrorPayloadObjectTypeResolver::class);
            $this->userIsLoggedInErrorPayloadObjectTypeResolver = $userIsLoggedInErrorPayloadObjectTypeResolver;
        }
        return $this->userIsLoggedInErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserIsLoggedInErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserIsLoggedInErrorPayload::class;
    }
}
