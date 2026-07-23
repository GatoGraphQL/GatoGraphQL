<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\UserRoleDoesNotExistErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UserRoleDoesNotExistErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserRoleDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UserRoleDoesNotExistErrorPayloadObjectTypeResolver $userRoleDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getUserRoleDoesNotExistErrorPayloadObjectTypeResolver(): UserRoleDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->userRoleDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var UserRoleDoesNotExistErrorPayloadObjectTypeResolver */
            $userRoleDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UserRoleDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->userRoleDoesNotExistErrorPayloadObjectTypeResolver = $userRoleDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->userRoleDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUserRoleDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UserRoleDoesNotExistErrorPayload::class;
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
