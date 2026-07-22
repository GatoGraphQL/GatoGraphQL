<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToEditUserRolesErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToEditUserRolesErrorPayload::class;
    }

    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
