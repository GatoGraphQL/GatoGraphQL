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

    final protected function getInvalidUsernameErrorPayloadObjectTypeResolver(): InvalidUsernameErrorPayloadObjectTypeResolver
    {
        if ($this->userIsNotLoggedInErrorPayloadObjectTypeResolver === null) {
            /** @var InvalidUsernameErrorPayloadObjectTypeResolver */
            $userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(InvalidUsernameErrorPayloadObjectTypeResolver::class);
            $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
        }
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver;
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
