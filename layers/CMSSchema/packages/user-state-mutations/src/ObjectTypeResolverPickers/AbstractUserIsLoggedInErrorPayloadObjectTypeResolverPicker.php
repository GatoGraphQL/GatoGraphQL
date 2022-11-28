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

    final public function setUserIsLoggedInErrorPayloadObjectTypeResolver(UserIsLoggedInErrorPayloadObjectTypeResolver $userIsLoggedInErrorPayloadObjectTypeResolver): void
    {
        $this->userIsLoggedInErrorPayloadObjectTypeResolver = $userIsLoggedInErrorPayloadObjectTypeResolver;
    }
    final protected function getUserIsLoggedInErrorPayloadObjectTypeResolver(): UserIsLoggedInErrorPayloadObjectTypeResolver
    {
        /** @var UserIsLoggedInErrorPayloadObjectTypeResolver */
        return $this->userIsLoggedInErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(UserIsLoggedInErrorPayloadObjectTypeResolver::class);
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
