<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PasswordIsIncorrectErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?PasswordIsIncorrectErrorPayloadObjectTypeDataLoader $passwordIsIncorrectErrorPayloadObjectTypeDataLoader = null;

    final protected function getPasswordIsIncorrectErrorPayloadObjectTypeDataLoader(): PasswordIsIncorrectErrorPayloadObjectTypeDataLoader
    {
        if ($this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader === null) {
            /** @var PasswordIsIncorrectErrorPayloadObjectTypeDataLoader */
            $passwordIsIncorrectErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(PasswordIsIncorrectErrorPayloadObjectTypeDataLoader::class);
            $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader = $passwordIsIncorrectErrorPayloadObjectTypeDataLoader;
        }
        return $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader;
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
