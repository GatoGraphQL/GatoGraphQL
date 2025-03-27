<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\AccessToMetaKeyIsNotAllowedErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver = null;

    final protected function getAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver(): AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver
    {
        if ($this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver === null) {
            /** @var AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver */
            $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(AccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver::class);
            $this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver = $accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver;
        }
        return $this->accessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getAccessToMetaKeyIsNotAllowedErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return AccessToMetaKeyIsNotAllowedErrorPayload::class;
    }
}
