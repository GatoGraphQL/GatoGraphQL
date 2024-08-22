<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_Site;

use function get_sites;

class SettingsTypeAPI implements SettingsTypeAPIInterface
{
    use BasicServiceTrait;

    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Indicates if the passed object is of type Comment
     */
    public function isInstanceOfNetworkSiteType(object $object): bool
    {
        return $object instanceof WP_Site;
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getNetworkSites(array $query, array $options = []): array
    {
        $query = $this->convertNetworkSitesQuery($query, $options);
        return (array) get_sites($query);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     *
     * @see https://developer.wordpress.org/reference/classes/WP_Site_Query/__construct/
     */
    protected function convertNetworkSitesQuery(array $query, array $options): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }

        $query['archived'] = 0;
        $query['spam'] = 0;
        $query['deleted'] = 0;

        $query = App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
        return $query;
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getNetworkSiteCount(array $query, array $options = []): int
    {
        $query = $this->convertNetworkSitesQuery($query, $options);
        $query['number'] = 0;
        unset($query['offset']);
        $query['count'] = true;
        /** @var int */
        $count = get_sites($query);
        return $count;
    }

    public function getNetworkSiteID(object $networkSite): string|int
    {
        /** @var WP_Site $networkSite */
        return (int) $networkSite->blog_id;
    }
}
