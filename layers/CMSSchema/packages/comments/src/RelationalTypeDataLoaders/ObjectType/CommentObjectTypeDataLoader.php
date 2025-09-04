<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectTypeQueryableDataLoaderTrait;
use PoP\ComponentModel\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;

class CommentObjectTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    use ObjectTypeQueryableDataLoaderTrait;

    public const HOOK_ALL_OBJECTS_BY_IDS_QUERY = __CLASS__ . ':all-objects-by-ids-query';

    private ?CommentTypeAPIInterface $commentTypeAPI = null;

    final protected function getCommentTypeAPI(): CommentTypeAPIInterface
    {
        if ($this->commentTypeAPI === null) {
            /** @var CommentTypeAPIInterface */
            $commentTypeAPI = $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
            $this->commentTypeAPI = $commentTypeAPI;
        }
        return $this->commentTypeAPI;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return App::applyFilters(
            self::HOOK_ALL_OBJECTS_BY_IDS_QUERY,
            [
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
                'custompost-status' => $this->getAllCustomPostStatuses(),
            ],
            $ids
        );
    }

    /**
     * @return string[]
     */
    protected function getAllCustomPostStatuses(): array
    {
        return [
            CustomPostStatus::PUBLISH,
            CustomPostStatus::PENDING,
            CustomPostStatus::DRAFT,
            CustomPostStatus::TRASH,
        ];
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return $this->getCommentTypeAPI()->getComments($query, $options);
    }
}
