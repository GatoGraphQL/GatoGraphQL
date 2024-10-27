<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\PasswordIsIncorrectErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractPasswordIsIncorrectErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?PasswordIsIncorrectErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver = null;

    final protected function getPasswordIsIncorrectErrorPayloadObjectTypeResolver(): PasswordIsIncorrectErrorPayloadObjectTypeResolver
    {
        if ($this->userIsNotLoggedInErrorPayloadObjectTypeResolver === null) {
            /** @var PasswordIsIncorrectErrorPayloadObjectTypeResolver */
            $userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(PasswordIsIncorrectErrorPayloadObjectTypeResolver::class);
            $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
        }
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPasswordIsIncorrectErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return PasswordIsIncorrectErrorPayload::class;
    }
}
