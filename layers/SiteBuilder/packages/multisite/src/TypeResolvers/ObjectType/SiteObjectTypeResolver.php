<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Multisite\ObjectModels\Site;
use PoP\Multisite\RelationalTypeDataLoaders\ObjectType\SiteObjectTypeDataLoader;

class SiteObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?SiteObjectTypeDataLoader $siteObjectTypeDataLoader = null;

    final public function setSiteObjectTypeDataLoader(SiteObjectTypeDataLoader $siteObjectTypeDataLoader): void
    {
        $this->siteObjectTypeDataLoader = $siteObjectTypeDataLoader;
    }
    final protected function getSiteObjectTypeDataLoader(): SiteObjectTypeDataLoader
    {
        /** @var SiteObjectTypeDataLoader */
        return $this->siteObjectTypeDataLoader ??= $this->instanceManager->getInstance(SiteObjectTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Site';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Obtain properties belonging to the site (name, domain, configuration options, etc)', 'multisite');
    }

    public function getID(object $object): string|int|null
    {
        /** @var Site */
        $site = $object;
        return $site->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSiteObjectTypeDataLoader();
    }
}
