<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\ComponentProcessors\FilterDataComponentProcessorInterface;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\Root\App;

abstract class AbstractObjectTypeQueryableDataLoader extends AbstractObjectTypeDataLoader implements ObjectTypeQueryableDataLoaderInterface
{
    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        if ($this->componentProcessorManager === null) {
            /** @var ComponentProcessorManagerInterface */
            $componentProcessorManager = $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
            $this->componentProcessorManager = $componentProcessorManager;
        }
        return $this->componentProcessorManager;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    abstract public function executeQuery(array $query, array $options = []): array;

    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs(array $query): array
    {
        return $this->executeQuery($query);
    }

    /**
     * @param array<string,mixed> $query_args
     */
    protected function getPagenumberParam(array $query_args): int
    {
        // @todo convert the hook from string to const, then re-enable
        // return App::applyFilters(
        //     'GD_Dataloader_List:query:pagenumber',
        //     (int)$query_args[PaginationParams::PAGE_NUMBER]
        // );
        return (int)$query_args[PaginationParams::PAGE_NUMBER];
    }

    /**
     * @param array<string,mixed> $query_args
     */
    protected function getLimitParam(array $query_args): int
    {
        // @todo convert the hook from string to const, then re-enable
        // return App::applyFilters(
        //     'GD_Dataloader_List:query:limit',
        //     (int)$query_args[PaginationParams::LIMIT]
        // );
        return (int)$query_args[PaginationParams::LIMIT];
    }

    /**
     * @param array<string,mixed> $data_properties
     * @return array<string|int>
     */
    public function findIDs(array $data_properties): array
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        // If already indicating the ids to get back, then already return them
        if ($include = $query_args['include'] ?? null) {
            return $include;
        }

        // Customize query
        $query = $this->getQuery($query_args);

        // Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
        $query = App::applyFilters(self::class . ':gd_dataload_query', $query, $data_properties);

        // Apply filtering of the data
        if ($filtering_components = $data_properties[DataloadingConstants::QUERYARGSFILTERINGCOMPONENTS] ?? null) {
            /** @var Component[] $filtering_components */
            $componentProcessorManager = $this->getComponentProcessorManager();
            foreach ($filtering_components as $component) {
                /** @var FilterDataComponentProcessorInterface */
                $filterDataComponentProcessor = $componentProcessorManager->getComponentProcessor($component);
                $filterDataComponentProcessor->filterHeadcomponentDataloadQueryArgs($component, $query);
            }
        }

        // Execute the query, get ids
        $ids = $this->executeQueryIDs($query);

        return $ids;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    abstract public function getQueryToRetrieveObjectsForIDs(array $ids): array;

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        $query = App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            $this->getQueryToRetrieveObjectsForIDs($ids),
            $ids
        );
        return $this->executeQuery($query);
    }

    protected function getOrderbyDefault(): string
    {
        return '';
    }

    protected function getOrderDefault(): string
    {
        return '';
    }

    protected function getQueryHookName(): string
    {
        return 'Dataloader_ListTrait:query';
    }

    /**
     * @param array<string,mixed> $query_args
     * @return array<string,mixed>
     */
    public function getQuery(array $query_args): array
    {
        // Use all the query params already provided in the query args
        $query = $query_args;

        // Allow to check for "loading-latest"
        $limit = $this->getLimitParam($query_args);
        $query['limit'] = $limit;
        $pagenumber = $this->getPagenumberParam($query_args);
        if ($pagenumber >= 2) {
            $query['offset'] = ($pagenumber - 1) * $limit;
        }
        // Params and values by default
        if (!isset($query['orderby'])) {
            $query['orderby'] = $this->getOrderbyDefault();
        }
        if (!isset($query['order'])) {
            $query['order'] = $this->getOrderDefault();
        }

        // Allow CoAuthors Plus to modify the query to add the coauthors
        return App::applyFilters(
            $this->getQueryHookName(),
            $query,
            $query_args
        );
    }
}
