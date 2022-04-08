<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use GraphQLAPI\WPFakerSchema\App;
use GraphQLAPI\WPFakerSchema\Component;
use GraphQLAPI\WPFakerSchema\ComponentConfiguration;
use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use PoPCMSSchema\CommentsWP\TypeAPIs\CommentTypeAPI as UpstreamCommentTypeAPI;

use WP_Comment;

class CommentTypeAPI extends UpstreamCommentTypeAPI
{
    use TypeAPITrait;

    private ?DataProviderInterface $dataProvider = null;

    final public function setDataProvider(DataProviderInterface $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }
    final protected function getDataProvider(): DataProviderInterface
    {
        return $this->dataProvider ??= $this->instanceManager->getInstance(DataProviderInterface::class);
    }

    protected function resolveGetComments(array $query): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useFixedDataset = $componentConfiguration->useFixedDataset();

        $retrieveCommentIDs = $this->retrieveCommentIDs($query);

        /**
         * If providing the IDs to retrieve, re-generate exactly those objects.
         */
        $ids = $query['include'] ?? null;
        if (!empty($ids)) {
            /** @var int[] */
            $commentIDs = is_string($ids) ? array_map(
                fn (string $id) => (int) trim($id),
                explode(',', $ids)
            ) : $ids;
            /**
             * If using a fixed dataset, make sure the ID exists.
             * If it does not, return `null` instead
             */
            if ($useFixedDataset) {
                $commentIDs = array_values(array_intersect(
                    $commentIDs,
                    $this->getFakeCommentIDs()
                ));
            }
            if ($retrieveCommentIDs) {
                return $commentIDs;
            }
            return $useFixedDataset
                ? $this->getFakeComments($commentIDs)
                : array_map(
                    fn (string|int $id) => App::getWPFaker()->comment([
                        // The ID is provided, the rest is random data
                        'id' => $id
                    ]),
                    $commentIDs
                );
        }

        /**
         * Get comments from the fixed dataset?
         */
        if ($useFixedDataset) {
            $commentDataEntries = $this->getFakeCommentDataEntries();
            $filterableProperties = [
                'comment_post_id',
                'comment_type',
                'parent' => 'comment_parent',
            ];
            foreach ($filterableProperties as $queryProperty => $dataProperty) {
                if (is_numeric($queryProperty)) {
                    $queryProperty = $dataProperty;
                }
                if (!isset($query[$queryProperty])) {
                    continue;
                }
                $commentDataEntries = $this->filterCommentDataEntriesByProperty(
                    $commentDataEntries,
                    $dataProperty,
                    $query[$queryProperty]
                );
            }
            $number = (int) ($query['number'] ?? 10);
            $offset = (int) ($query['offset'] ?? 0);
            if ($number !== 0) {
                $commentDataEntries = array_slice(
                    $commentDataEntries,
                    $offset,
                    $number,
                );
            } elseif ($offset !== 0) {
                $commentDataEntries = array_slice(
                    $commentDataEntries,
                    $offset,
                );
            }
            $commentIDs = array_map(
                fn (array $commentDataEntry): int => $commentDataEntry['id'],
                $commentDataEntries,
            );
            if ($retrieveCommentIDs) {
                return $commentIDs;
            }
            return $this->getFakeComments($commentIDs);
        }

        /**
         * Otherwise, let BrainFaker produce random entries
         */
        $comments = App::getWPFaker()->comments($query['number'] ?? 10);
        if ($retrieveCommentIDs) {
            return array_map(
                fn (WP_Comment $comment) => $comment->ID,
                $comments
            );
        }
        return $comments;
    }

    protected function retrieveCommentIDs(array $query): bool
    {
        return ($query['fields'] ?? null) === 'ids';
    }

    /**
     * @param int[] $commentIDs
     * @return WP_Comment[]
     */
    protected function getFakeComments(array $commentIDs): array
    {
        return array_map(
            fn (array $fakeCommentDataEntry) => App::getWPFaker()->comment($fakeCommentDataEntry),
            $this->getFakeCommentDataEntries($commentIDs)
        );
    }
    
    /**
     * @return int[] $commentIDs
     */
    protected function getFakeCommentIDs(): array
    {
        return array_values(array_map(
            fn (array $commentDataEntry) => (int) $commentDataEntry['comment_id'],
            $this->getAllFakeCommentDataEntries()
        ));
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getAllFakeCommentDataEntries(): array
    {
        $comments = [];
        foreach ($this->getDataProvider()->getFixedDataset()['posts'] ?? [] as $postDataEntry) {
            $postComments = $postDataEntry['comments'] ?? [];
            $comments = [
                ...$comments,
                ...$postComments
            ];
        }
        return $comments;
    }

    /**
     * @param int[] $commentIDs
     * @return array<string,mixed>
     */
    protected function getFakeCommentDataEntries(array $commentIDs = []): array
    {
        $commentDataEntries = $this->getAllFakeCommentDataEntries();
        if ($commentIDs !== []) {
            array_filter(
                $commentDataEntries,
                fn (array $commentDataEntry) => in_array($commentDataEntry['comment_id'], $commentIDs)
            );
        }

        // $properties = [
        //     'comment_title',
        //     'guid',
        //     'comment_author',
        //     'comment_content',
        //     'comment_excerpt',
        //     'comment_id',
        //     'comment_date',
        //     'comment_date_gmt',
        //     'comment_status',
        //     'ping_status',
        //     'comment_name',
        //     'status',
        //     'comment_parent',
        //     'menu_order',
        //     'comment_type',
        //     'comment_password',
        //     'is_sticky',
        //     'terms',
        // ];

        /**
         * Convert some properties, keep all others the same
         */
        return array_map(
            fn (array $commentDataEntry) => [
                ...$commentDataEntry,
                ...[
                    'id' => $commentDataEntry['comment_id'],
                    'user_id' => $commentDataEntry['comment_user_id'],
                ]
            ],
            $commentDataEntries
        );
    }

    protected function resolveGetComment(int | string $id): ?WP_Comment
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useFixedDataset = $componentConfiguration->useFixedDataset();

        if ($useFixedDataset) {
            $commentDataEntries = $this->getFakeCommentDataEntries();
            if ($commentDataEntries === []) {
                return null;
            }
            $commentIDs = array_map(
                fn (array $commentDataEntry): int => $commentDataEntry['id'],
                $commentDataEntries,
            );
            $comments = $this->getFakeComments($commentIDs);
            return $comments[0];
        }

        return App::getWPFaker()->comment([
            // Create a random new comment with the requested ID
            'id' => $id
        ]);
    }

    protected function filterCommentDataEntriesByProperty(array $commentDataEntries, string $property, string|int|array $propertyValueOrValues): array
    {
        $propertyValues = is_array($propertyValueOrValues) ? $propertyValueOrValues : [$propertyValueOrValues];
        return array_values(array_filter(array_map(
            fn (array $fakeCommentDataEntry): ?array => in_array($fakeCommentDataEntry[$property] ?? null, $propertyValues) ? $fakeCommentDataEntry : null,
            $commentDataEntries,
        )));
    }
}
