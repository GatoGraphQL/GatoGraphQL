<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

// use PoPSchema\CustomPosts\Types\Status;

class MediaTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getObjects(array $ids): array
    {
        $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
        $query = array(
            'include' => $ids,
        );
        return $cmsmediaapi->getMediaElements($query);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array();
        $query['include'] = $ids;
        // $query['status'] = [
        //     Status::PUBLISHED,
        // ];
        $query['custompost-types'] = 'attachment';

        return $query;
    }

    public function executeQuery($query, array $options = [])
    {
        $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
        return $cmsmediaapi->getMediaElements($query, $options);
    }

    public function executeQueryIds($query): array
    {
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
