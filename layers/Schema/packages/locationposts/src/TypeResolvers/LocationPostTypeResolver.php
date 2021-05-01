<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\TypeResolvers;

use PoPSchema\LocationPosts\Environment;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\LocationPosts\TypeDataLoaders\LocationPostTypeDataLoader;

class LocationPostTypeResolver extends PostTypeResolver
{
    protected static ?string $name = null;

    public function getTypeName(): string
    {
        if (is_null(self::$name)) {
            self::$name = Environment::getLocationPostTypeName() ?? 'LocationPost';
        }
        return self::$name;
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('A post which has locations', 'locationposts');
    }

    public function getTypeDataLoaderClass(): string
    {
        return LocationPostTypeDataLoader::class;
    }
}
