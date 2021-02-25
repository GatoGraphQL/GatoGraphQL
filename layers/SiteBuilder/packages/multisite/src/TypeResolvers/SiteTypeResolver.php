<?php

declare(strict_types=1);

namespace PoP\Multisite\TypeResolvers;

use PoP\Multisite\TypeDataLoaders\SiteTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class SiteTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Site';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Obtain properties belonging to the site (name, domain, configuration options, etc)', 'multisite');
    }

    public function getID(object $resultItem)
    {
        $site = $resultItem;
        return $site->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return SiteTypeDataLoader::class;
    }
}
