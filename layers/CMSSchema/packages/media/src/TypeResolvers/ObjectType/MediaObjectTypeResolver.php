<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaObjectTypeDataLoader;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;

class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaObjectTypeDataLoader $mediaObjectTypeDataLoader = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    final public function setMediaObjectTypeDataLoader(MediaObjectTypeDataLoader $mediaObjectTypeDataLoader): void
    {
        $this->mediaObjectTypeDataLoader = $mediaObjectTypeDataLoader;
    }
    final protected function getMediaObjectTypeDataLoader(): MediaObjectTypeDataLoader
    {
        /** @var MediaObjectTypeDataLoader */
        return $this->mediaObjectTypeDataLoader ??= $this->instanceManager->getInstance(MediaObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Media';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }

    public function getID(object $object): string|int|null
    {
        $media = $object;
        return $this->getMediaTypeAPI()->getMediaItemID($media);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaObjectTypeDataLoader();
    }
}
