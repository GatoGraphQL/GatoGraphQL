<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers\ObjectType;

use PoP\Multisite\RelationalTypeDataLoaders\ObjectType\SiteTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

class SiteObjectTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'Site';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Obtain properties belonging to the site (name, domain, configuration options, etc)', 'multisite');
    }

    public function getID(object $object): string | int | null
    {
        $site = $object;
        return $site->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SiteTypeDataLoader::class;
    }
}
