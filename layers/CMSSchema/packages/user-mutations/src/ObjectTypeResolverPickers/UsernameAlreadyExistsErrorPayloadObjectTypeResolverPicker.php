<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\UsernameAlreadyExistsErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\UsernameAlreadyExistsErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UsernameAlreadyExistsErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?UsernameAlreadyExistsErrorPayloadObjectTypeResolver $usernameAlreadyExistsErrorPayloadObjectTypeResolver = null;

    final protected function getUsernameAlreadyExistsErrorPayloadObjectTypeResolver(): UsernameAlreadyExistsErrorPayloadObjectTypeResolver
    {
        if ($this->usernameAlreadyExistsErrorPayloadObjectTypeResolver === null) {
            /** @var UsernameAlreadyExistsErrorPayloadObjectTypeResolver */
            $usernameAlreadyExistsErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(UsernameAlreadyExistsErrorPayloadObjectTypeResolver::class);
            $this->usernameAlreadyExistsErrorPayloadObjectTypeResolver = $usernameAlreadyExistsErrorPayloadObjectTypeResolver;
        }
        return $this->usernameAlreadyExistsErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getUsernameAlreadyExistsErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return UsernameAlreadyExistsErrorPayload::class;
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
