<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Multisite\ObjectModels\Site;
use PoP\Multisite\RelationalTypeDataLoaders\ObjectType\SiteTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class SiteObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?SiteTypeDataLoader $siteTypeDataLoader = null;

    public function setSiteTypeDataLoader(SiteTypeDataLoader $siteTypeDataLoader): void
    {
        $this->siteTypeDataLoader = $siteTypeDataLoader;
    }
    protected function getSiteTypeDataLoader(): SiteTypeDataLoader
    {
        return $this->siteTypeDataLoader ??= $this->instanceManager->getInstance(SiteTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'Site';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('Obtain properties belonging to the site (name, domain, configuration options, etc)', 'multisite');
    }

    public function getID(object $object): string | int | null
    {
        /** @var Site */
        $site = $object;
        return $site->getID();
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getSiteTypeDataLoader();
    }
}
