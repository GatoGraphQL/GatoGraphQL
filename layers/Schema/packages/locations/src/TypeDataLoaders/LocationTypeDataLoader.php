<?php

declare(strict_types=1);

namespace PoPSchema\Locations\TypeDataLoaders;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class LocationTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getObjects(array $ids): array
    {
        $pluginapi = \PoP_Locations_APIFactory::getInstance();
        $query = array(
            'include' => $ids,
            'limit' => -1,
        );
        return $pluginapi->getLocations($query);
    }

    public function executeQueryIds($query): array
    {
        return (array)$this->executeQuery($query, ['return-type' => ReturnTypes::IDS]);
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcomponent:locations:dbcolumn:orderby:locations:name');
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids,
            'limit' => -1,
        );
        return $query;
    }

    public function executeQuery($query, array $options = [])
    {
        $pluginapi = \PoP_Locations_APIFactory::getInstance();
        return $pluginapi->getLocations($query, $options);
    }
}
