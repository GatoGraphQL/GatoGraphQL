<?php

declare(strict_types=1);

namespace PoPSchema\Comments\RelationalTypeDataLoaders\Object;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeQueryableDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\Comments\Constants\CommentStatus;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class CommentTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
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

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
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

    public function executeQuery($query, array $options = []): array
    {
        return $this->commentTypeAPI->getComments($query, $options);
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
