<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\RelationalTypeDataLoaders\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoP_Notifications_API;

class NotificationTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    protected function getOrderbyDefault(): string
    {
        return 'histid';
    }

    protected function getOrderDefault(): string
    {
        return 'DESC';
    }

    /**
     * @param array<string,mixed> $query_args
     * @return array<string,mixed>
     */
    public function getQuery(array $query_args): array
    {
        $query = parent::getQuery($query_args);

        // Pagination
        if (App::hasState('loading-latest') && App::getState('loading-latest')) {
            $query['pagenumber'] = 1;
            $query['limit'] = -1; // Limit=-1 => Bring all results
        } else {
            if ($pagenumber = $query_args[PaginationParams::PAGE_NUMBER]) {
                $query['pagenumber'] = $pagenumber;
            }
            if ($limit = $query_args[PaginationParams::LIMIT]) {
                $query['limit'] = $limit;
            }
        }

        return App::applyFilters(
            $this->getQueryHookName(),
            $query,
            $query_args
        );
    }

    protected function getQueryHookName(): string
    {
        return 'Dataloader_NotificationList:query';
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return array(
            'include' => $ids,
        );
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs(array $query): array
    {
        $query['array'] = true;
        $query['fields'] = array('histid');

        // By default, we use joinstatus => false to make the initial query run faster
        // however, this param can be provided in the dataload_query_args,
        // eg: to bring in only notifications which have not been read, for the automated emails daily notification digest
        // then keep it as it is
        if (!isset($query['joinstatus'])) {
            $query['joinstatus'] = false;
        }

        $results = $this->executeQuery($query);

        $ret = array();
        if ($results) {
            foreach ($results as $value) {
                $ret[] = $value['histid'];
            }
        }

        return $ret;
    }

    /**
     * @return mixed[]
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return PoP_Notifications_API::getNotifications($query);
    }
}
