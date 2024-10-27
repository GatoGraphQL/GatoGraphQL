<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\InvalidUserEmailErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\InvalidUserEmailErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractInvalidUserEmailErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?InvalidUserEmailErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver = null;

    final protected function getInvalidUserEmailErrorPayloadObjectTypeResolver(): InvalidUserEmailErrorPayloadObjectTypeResolver
    {
        if ($this->userIsNotLoggedInErrorPayloadObjectTypeResolver === null) {
            /** @var InvalidUserEmailErrorPayloadObjectTypeResolver */
            $userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(InvalidUserEmailErrorPayloadObjectTypeResolver::class);
            $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
        }
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getInvalidUserEmailErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return InvalidUserEmailErrorPayload::class;
    }
}
