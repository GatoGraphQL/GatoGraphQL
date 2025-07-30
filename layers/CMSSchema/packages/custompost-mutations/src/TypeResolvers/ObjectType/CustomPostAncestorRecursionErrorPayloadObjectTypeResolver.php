<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\CustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostAncestorRecursionErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?CustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader $customPostAncestorRecursionErrorPayloadObjectTypeDataLoader = null;

    final protected function getCustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader(): CustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostAncestorRecursionErrorPayloadObjectTypeDataLoader === null) {
            /** @var CustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader */
            $customPostAncestorRecursionErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader::class);
            $this->customPostAncestorRecursionErrorPayloadObjectTypeDataLoader = $customPostAncestorRecursionErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostAncestorRecursionErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostAncestorRecursionErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The custom post is an ancestor of itself"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostAncestorRecursionErrorPayloadObjectTypeDataLoader();
    }
}
