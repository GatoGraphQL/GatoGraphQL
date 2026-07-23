<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\EmailAlreadyExistsErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EmailAlreadyExistsErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EmailAlreadyExistsErrorPayloadObjectTypeDataLoader $emailAlreadyExistsErrorPayloadObjectTypeDataLoader = null;

    final protected function getEmailAlreadyExistsErrorPayloadObjectTypeDataLoader(): EmailAlreadyExistsErrorPayloadObjectTypeDataLoader
    {
        if ($this->emailAlreadyExistsErrorPayloadObjectTypeDataLoader === null) {
            /** @var EmailAlreadyExistsErrorPayloadObjectTypeDataLoader */
            $emailAlreadyExistsErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EmailAlreadyExistsErrorPayloadObjectTypeDataLoader::class);
            $this->emailAlreadyExistsErrorPayloadObjectTypeDataLoader = $emailAlreadyExistsErrorPayloadObjectTypeDataLoader;
        }
        return $this->emailAlreadyExistsErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EmailAlreadyExistsErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The email already exists"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEmailAlreadyExistsErrorPayloadObjectTypeDataLoader();
    }
}
