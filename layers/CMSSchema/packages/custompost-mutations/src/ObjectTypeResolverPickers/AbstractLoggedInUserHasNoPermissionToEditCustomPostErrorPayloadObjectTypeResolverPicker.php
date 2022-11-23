<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker implements LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPickerInterface
{
    private ?LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver
    {
        /** @var LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver */
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver::class);
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
