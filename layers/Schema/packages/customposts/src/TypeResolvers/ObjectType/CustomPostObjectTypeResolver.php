<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    private ?CustomPostTypeDataLoader $customPostTypeDataLoader = null;

    public function setCustomPostTypeDataLoader(CustomPostTypeDataLoader $customPostTypeDataLoader): void
    {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }
    protected function getCustomPostTypeDataLoader(): CustomPostTypeDataLoader
    {
        return $this->customPostTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Representation of a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostTypeDataLoader();
    }
}
