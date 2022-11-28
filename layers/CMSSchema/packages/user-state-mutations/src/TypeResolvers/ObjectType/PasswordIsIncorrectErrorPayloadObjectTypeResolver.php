<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PasswordIsIncorrectErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?PasswordIsIncorrectErrorPayloadObjectTypeDataLoader $passwordIsIncorrectErrorPayloadObjectTypeDataLoader = null;

    final public function setPasswordIsIncorrectErrorPayloadObjectTypeDataLoader(PasswordIsIncorrectErrorPayloadObjectTypeDataLoader $passwordIsIncorrectErrorPayloadObjectTypeDataLoader): void
    {
        $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader = $passwordIsIncorrectErrorPayloadObjectTypeDataLoader;
    }
    final protected function getPasswordIsIncorrectErrorPayloadObjectTypeDataLoader(): PasswordIsIncorrectErrorPayloadObjectTypeDataLoader
    {
        /** @var PasswordIsIncorrectErrorPayloadObjectTypeDataLoader */
        return $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader ??= $this->instanceManager->getInstance(PasswordIsIncorrectErrorPayloadObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PasswordIsIncorrectErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The password is incorrect"', 'user-state-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPasswordIsIncorrectErrorPayloadObjectTypeDataLoader();
    }
}
