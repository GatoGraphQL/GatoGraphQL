<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;

class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?MediaTypeDataLoader $mediaTypeDataLoader = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    final public function setMediaTypeDataLoader(MediaTypeDataLoader $mediaTypeDataLoader): void
    {
        $this->mediaTypeDataLoader = $mediaTypeDataLoader;
    }
    final protected function getMediaTypeDataLoader(): MediaTypeDataLoader
    {
        /** @var MediaTypeDataLoader */
        return $this->mediaTypeDataLoader ??= $this->instanceManager->getInstance(MediaTypeDataLoader::class);
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
        return $this->getMediaTypeDataLoader();
    }
}
