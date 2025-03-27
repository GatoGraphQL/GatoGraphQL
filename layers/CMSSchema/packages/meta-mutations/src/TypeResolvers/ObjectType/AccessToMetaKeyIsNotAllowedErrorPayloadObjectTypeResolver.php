<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader = null;

    final protected function getAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader(): AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader
    {
        if ($this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader === null) {
            /** @var AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader */
            $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader::class);
            $this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader = $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader;
        }
        return $this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'AccessToMetaKeyIsNotAllowedErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Access to the meta key is not allowed"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeDataLoader();
    }
}
