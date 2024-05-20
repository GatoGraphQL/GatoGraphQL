<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers\AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker;
use PoPCMSSchema\PageMutations\ObjectModels\LoggedInUserHasNoPermissionToEditPageErrorPayload;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolverPicker extends AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver(LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditPageErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditPageErrorPayload::class;
    }
}
