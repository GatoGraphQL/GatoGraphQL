<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\MediaItemHasAlreadyBeenTrashedErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = null;

    final protected function getMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver(): MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver
    {
        if ($this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver === null) {
            /** @var MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver */
            $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver::class);
            $this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
        }
        return $this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return MediaItemHasAlreadyBeenTrashedErrorPayload::class;
    }
}
