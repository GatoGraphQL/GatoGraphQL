<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers\ObjectType;

use PoP\Multisite\RelationalTypeDataLoaders\ObjectType\SiteTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

class SiteTypeResolver extends AbstractObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'Site';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Obtain properties belonging to the site (name, domain, configuration options, etc)', 'multisite');
    }

    public function getID(object $resultItem): string | int | null
    {
        $site = $resultItem;
        return $site->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SiteTypeDataLoader::class;
    }
}
