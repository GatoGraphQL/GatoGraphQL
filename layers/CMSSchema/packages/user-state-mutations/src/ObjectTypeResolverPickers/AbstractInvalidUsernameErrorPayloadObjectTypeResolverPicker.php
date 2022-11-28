<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\InvalidUsernameErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\InvalidUsernameErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractInvalidUsernameErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?InvalidUsernameErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver = null;

    final public function setInvalidUsernameErrorPayloadObjectTypeResolver(InvalidUsernameErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver): void
    {
        $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }
    final protected function getInvalidUsernameErrorPayloadObjectTypeResolver(): InvalidUsernameErrorPayloadObjectTypeResolver
    {
        /** @var InvalidUsernameErrorPayloadObjectTypeResolver */
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(InvalidUsernameErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getInvalidUsernameErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return InvalidUsernameErrorPayload::class;
    }
}
