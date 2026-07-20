<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\MediaItemDoesNotSupportTrashErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractMediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver = null;

    final protected function getMediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver(): MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver
    {
        if ($this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver === null) {
            /** @var MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver */
            $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver::class);
            $this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver = $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver;
        }
        return $this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getMediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return MediaItemDoesNotSupportTrashErrorPayload::class;
    }
}
