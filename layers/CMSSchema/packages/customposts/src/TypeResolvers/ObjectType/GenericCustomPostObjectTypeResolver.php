<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

/**
 * Class to be used only when a Generic Custom Post Type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 */
class GenericCustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?CustomPostTypeDataLoader $customPostTypeDataLoader = null;

    final public function setCustomPostTypeDataLoader(CustomPostTypeDataLoader $customPostTypeDataLoader): void
    {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }
    final protected function getCustomPostTypeDataLoader(): CustomPostTypeDataLoader
    {
        /** @var CustomPostTypeDataLoader */
        return $this->customPostTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostTypeDataLoader::class);
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
        return $this->getCustomPostTypeDataLoader();
    }
}
