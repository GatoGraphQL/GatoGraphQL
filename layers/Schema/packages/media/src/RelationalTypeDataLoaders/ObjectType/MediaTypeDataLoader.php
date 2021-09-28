<?php

declare(strict_types=1);

namespace PoPSchema\Media\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class MediaTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    protected MediaTypeAPIInterface $mediaTypeAPI;

    #[Required]
    public function autowireMediaTypeDataLoader(
        MediaTypeAPIInterface $mediaTypeAPI,
    ) {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
        ];
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->mediaTypeAPI->getMediaItems($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
