<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

// use PoPSchema\CustomPosts\Types\Status;

class MediaTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected MediaTypeAPIInterface $mediaTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjects(array $ids): array
    {
        $query = array(
            'include' => $ids,
        );
        return $this->mediaTypeAPI->getMediaElements($query);
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
        return $this->mediaTypeAPI->getMediaElements($query, $options);
    }

    public function executeQueryIds($query): array
    {
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
