<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers;

use PoPSchema\CustomPosts\TypeDataLoaders\CustomPostTypeDataLoader;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CustomPostTypeResolver extends AbstractCustomPostTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return CustomPostTypeDataLoader::class;
    }
}
