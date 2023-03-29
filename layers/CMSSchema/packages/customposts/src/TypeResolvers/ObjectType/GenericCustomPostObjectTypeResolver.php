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

    final public function setCustomPostObjectTypeDataLoader(CustomPostObjectTypeDataLoader $customPostObjectTypeDataLoader): void
    {
        $this->customPostObjectTypeDataLoader = $customPostObjectTypeDataLoader;
    }
    final protected function getCustomPostObjectTypeDataLoader(): CustomPostObjectTypeDataLoader
    {
        /** @var CustomPostObjectTypeDataLoader */
        return $this->customPostObjectTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostObjectTypeDataLoader::class);
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
