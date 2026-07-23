<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\LoggedInUserHasNoPermissionToDeleteUserErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver(): LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoPermissionToDeleteUserErrorPayload::class;
    }

    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractDeleteUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
