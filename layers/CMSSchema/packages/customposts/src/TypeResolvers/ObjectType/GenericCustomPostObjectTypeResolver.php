<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

/**
 * Class to be used only when a Generic Custom Post Type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 */
class GenericCustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?CustomPostObjectTypeDataLoader $customPostObjectTypeDataLoader = null;

    final protected function getCustomPostObjectTypeDataLoader(): CustomPostObjectTypeDataLoader
    {
        if ($this->customPostObjectTypeDataLoader === null) {
            /** @var CustomPostObjectTypeDataLoader */
            $customPostObjectTypeDataLoader = $this->instanceManager->getInstance(CustomPostObjectTypeDataLoader::class);
            $this->customPostObjectTypeDataLoader = $customPostObjectTypeDataLoader;
        }
        return $this->customPostObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('A custom post that does not have its own type in the schema', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostObjectTypeDataLoader();
    }
}
