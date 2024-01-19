<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MediaMutations\ObjectModels\MediaItemSourceIsMissingErrorPayload;
use PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType\MediaItemSourceIsMissingErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractMediaItemSourceIsMissingErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?MediaItemSourceIsMissingErrorPayloadObjectTypeResolver $mediaItemSourceIsMissingErrorPayloadObjectTypeResolver = null;

    final public function setMediaItemSourceIsMissingErrorPayloadObjectTypeResolver(MediaItemSourceIsMissingErrorPayloadObjectTypeResolver $mediaItemSourceIsMissingErrorPayloadObjectTypeResolver): void
    {
        $this->mediaItemSourceIsMissingErrorPayloadObjectTypeResolver = $mediaItemSourceIsMissingErrorPayloadObjectTypeResolver;
    }
    final protected function getMediaItemSourceIsMissingErrorPayloadObjectTypeResolver(): MediaItemSourceIsMissingErrorPayloadObjectTypeResolver
    {
        if ($this->mediaItemSourceIsMissingErrorPayloadObjectTypeResolver === null) {
            /** @var MediaItemSourceIsMissingErrorPayloadObjectTypeResolver */
            $mediaItemSourceIsMissingErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(MediaItemSourceIsMissingErrorPayloadObjectTypeResolver::class);
            $this->mediaItemSourceIsMissingErrorPayloadObjectTypeResolver = $mediaItemSourceIsMissingErrorPayloadObjectTypeResolver;
        }
        return $this->mediaItemSourceIsMissingErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getMediaItemSourceIsMissingErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return MediaItemSourceIsMissingErrorPayload::class;
    }
}
