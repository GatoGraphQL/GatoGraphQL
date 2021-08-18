<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\Constants\Params;
use PoPSchema\Comments\Constants\Status;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CommentTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected CommentTypeAPIInterface $commentTypeAPI,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjects(array $ids): array
    {
        $query = [
            'include' => $ids,
            'type' => [
                CommentTypes::COMMENT,
                CommentTypes::TRACKBACK,
                CommentTypes::PINGBACK,
            ],
        ];
        return $this->commentTypeAPI->getComments($query);
    }

    public function getQuery($query_args): array
    {
        $query = parent::getQuery($query_args);
        $query['status'] = Status::APPROVED;
        return $query;
    }
    public function getDataFromIdsQuery(array $ids): array
    {
        return [
            'include' => $ids,
            'type' => [
                CommentTypes::COMMENT,
                CommentTypes::TRACKBACK,
                CommentTypes::PINGBACK,
            ],
        ];
    }

    public function executeQuery($query, array $options = [])
    {
        return $this->commentTypeAPI->getComments($query, $options);
    }

    public function executeQueryIds($query): array
    {
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
