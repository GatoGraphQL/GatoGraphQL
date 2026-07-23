<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToCreateUsersErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToCreateUsersErrorPayload::class;
    }

    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
