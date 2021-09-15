<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Multisite\ObjectModels\Site;
use PoP\Multisite\RelationalTypeDataLoaders\ObjectType\SiteTypeDataLoader;

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
        /** @var Site */
        $site = $object;
        return $site->getID();
    }

    public function getRelationalTypeDataLoaderClass(): string
    {
        return SiteTypeDataLoader::class;
    }
}
