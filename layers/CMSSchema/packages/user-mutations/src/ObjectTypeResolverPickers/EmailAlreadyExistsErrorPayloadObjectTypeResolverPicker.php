<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\EmailAlreadyExistsErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\EmailAlreadyExistsErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class EmailAlreadyExistsErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?EmailAlreadyExistsErrorPayloadObjectTypeResolver $emailAlreadyExistsErrorPayloadObjectTypeResolver = null;

    final protected function getEmailAlreadyExistsErrorPayloadObjectTypeResolver(): EmailAlreadyExistsErrorPayloadObjectTypeResolver
    {
        if ($this->emailAlreadyExistsErrorPayloadObjectTypeResolver === null) {
            /** @var EmailAlreadyExistsErrorPayloadObjectTypeResolver */
            $emailAlreadyExistsErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(EmailAlreadyExistsErrorPayloadObjectTypeResolver::class);
            $this->emailAlreadyExistsErrorPayloadObjectTypeResolver = $emailAlreadyExistsErrorPayloadObjectTypeResolver;
        }
        return $this->emailAlreadyExistsErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getEmailAlreadyExistsErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return EmailAlreadyExistsErrorPayload::class;
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
