<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPWPSchema\Multisite\TypeAPIs\MultisiteTypeAPIInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;

class NetworkSiteObjectTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    private ?MultisiteTypeAPIInterface $multisiteTypeAPI = null;

    final public function setMultisiteTypeAPI(MultisiteTypeAPIInterface $multisiteTypeAPI): void
    {
        $this->multisiteTypeAPI = $multisiteTypeAPI;
    }
    final protected function getMultisiteTypeAPI(): MultisiteTypeAPIInterface
    {
        if ($this->multisiteTypeAPI === null) {
            /** @var MultisiteTypeAPIInterface */
            $multisiteTypeAPI = $this->instanceManager->getInstance(MultisiteTypeAPIInterface::class);
            $this->multisiteTypeAPI = $multisiteTypeAPI;
        }
        return $this->multisiteTypeAPI;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'site__in' => $ids,
            'number' => '',
            // 'archived' => 0,
            // 'spam' => 0,
            // 'deleted' => 0,
        ];
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getMultisiteTypeAPI()->getNetworkSites($query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs(array $query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
