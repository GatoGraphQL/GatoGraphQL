<?php

declare(strict_types=1);

namespace PoPSchema\Locations\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\Locations\Facades\LocationTypeAPIFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class LocationTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getObjects(array $ids): array
    {
        $locationTypeAPI = LocationTypeAPIFacade::getInstance();
        $query = [
            'include' => $ids,
            'limit' => -1,
        ];
        return $locationTypeAPI->getLocations($query);
    }

    public function executeQueryIds($query): array
    {
        return (array)$this->executeQuery($query, ['return-type' => ReturnTypes::IDS]);
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcomponent:locations:dbcolumn:orderby:locations:name');
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
        $locationTypeAPI = LocationTypeAPIFacade::getInstance();
        return $locationTypeAPI->getLocations($query, $options);
    }
}
