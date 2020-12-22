<?php

declare(strict_types=1);

namespace PoPSchema\Events\TypeDataLoaders;

use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\Events\ModuleProcessors\RelationalFieldDataloadModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class EventTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [
            RelationalFieldDataloadModuleProcessor::class,
            RelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_FILTER_EVENTLIST
        ];
    }

    public function getObjects(array $ids): array
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $query = array(
            'include' => $ids,
            'scope' => 'all',
            'limit' => -1,
            'owner' => false,
            'status' => 'all'
        );
        return $eventTypeAPI->getEvents($query);
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcomponent:events:dbcolumn:orderby:events:startdate');
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    protected function getQueryHookName()
    {
        // Allow CoAuthors Plus to modify the query to add the coauthors
        return 'EventTypeDataLoader:query';
    }

    public function executeQuery($query, array $options = [])
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        return $eventTypeAPI->getEvents($query, $options);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        return [
            'include' => $ids,
            'scope' => 'all',
            'limit' => -1,
            'owner' => false,
            'status' => 'all',
        ];
    }

    public function executeQueryIds($query): array
    {
        return (array)$this->executeQuery(
            $query,
            [
                'return-type' => ReturnTypes::IDS,
            ]
        );
    }
}
