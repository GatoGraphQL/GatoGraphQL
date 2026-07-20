<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteMenuErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToDeleteMenuErrorPayload::class;
    }
}
