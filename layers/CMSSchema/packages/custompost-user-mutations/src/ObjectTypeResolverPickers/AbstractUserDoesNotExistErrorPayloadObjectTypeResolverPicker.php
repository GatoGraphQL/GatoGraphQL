<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostUserMutations\ObjectModels\UserDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostUserMutations\TypeResolvers\ObjectType\UserDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractUserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserDoesNotExistErrorPayloadObjectTypeResolver $mediaItemDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getUserDoesNotExistErrorPayloadObjectTypeResolver(): UserDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var UserDoesNotExistErrorPayloadObjectTypeResolver */
            $mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserDoesNotExistErrorPayload::class;
    }
}
