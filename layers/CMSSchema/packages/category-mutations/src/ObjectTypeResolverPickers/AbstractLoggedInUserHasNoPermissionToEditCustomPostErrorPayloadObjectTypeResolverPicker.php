<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditCustomPostErrorPayload::class;
    }
}
