<?php

declare(strict_types=1);

namespace PoPSchema\Locations\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Locations\TypeDataLoaders\LocationTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Locations\Facades\LocationTypeAPIFacade;

class LocationTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Location';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a location entity, with a name, address and coordinates', 'locations');
    }

    public function getID(object $resultItem)
    {
        $locationTypeAPI = LocationTypeAPIFacade::getInstance();
        return $locationTypeAPI->getID($resultItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return LocationTypeDataLoader::class;
    }
}
