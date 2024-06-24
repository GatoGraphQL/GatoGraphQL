<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\TypeAPIs;

interface MultisiteTypeAPIInterface
{
    public function isInstanceOfNetworkSiteType(object $object): bool;

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getNetworkSites(array $query, array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getNetworkSiteCount(array $query, array $options = []): int;
    public function getNetworkSiteID(object $networkSite): string|int;
}
