<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Comments\Constants\CommentStatus;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

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
            'status' => [
                CommentStatus::APPROVE,
                CommentStatus::HOLD,
                CommentStatus::SPAM,
                CommentStatus::TRASH,
            ],
        ];
        return $this->commentTypeAPI->getComments($query);
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
            'status' => [
                CommentStatus::APPROVE,
                CommentStatus::HOLD,
                CommentStatus::SPAM,
                CommentStatus::TRASH,
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
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
