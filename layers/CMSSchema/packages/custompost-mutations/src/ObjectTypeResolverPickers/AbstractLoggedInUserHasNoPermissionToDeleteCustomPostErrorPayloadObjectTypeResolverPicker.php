<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayload::class;
    }
}
