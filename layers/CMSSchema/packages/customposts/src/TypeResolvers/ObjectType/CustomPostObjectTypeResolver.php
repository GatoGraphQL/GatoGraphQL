<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?CustomPostTypeDataLoader $customPostTypeDataLoader = null;

    final public function setCustomPostTypeDataLoader(CustomPostTypeDataLoader $customPostTypeDataLoader): void
    {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }
    final protected function getCustomPostTypeDataLoader(): CustomPostTypeDataLoader
    {
        return $this->customPostTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Representation of a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostTypeDataLoader();
    }
}
