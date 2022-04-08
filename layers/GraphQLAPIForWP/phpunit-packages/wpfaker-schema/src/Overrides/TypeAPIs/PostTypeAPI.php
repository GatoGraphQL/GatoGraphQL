<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use GraphQLAPI\WPFakerSchema\App;
use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use PoPCMSSchema\PostsWP\TypeAPIs\PostTypeAPI as UpstreamPostTypeAPI;
use WP_Post;

class PostTypeAPI extends UpstreamPostTypeAPI
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

    protected function resolveGetPost(int | string $id): ?WP_Post
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useFixedDataset = $componentConfiguration->useFixedDataset();

        if ($useFixedDataset) {
            $postDataEntries = $this->getFakePostDataEntries();
            if ($postDataEntries === []) {
                return null;
            }
            $postIDs = array_map(
                fn (array $postDataEntry): int => $postDataEntry['id'],
                $postDataEntries,
            );
            $posts = $this->getFakePosts($postIDs);
            return $posts[0];
        }

        return App::getWPFaker()->post([
            // Create a random new post with the requested ID
            'id' => $id
        ]);
    }

    /**
     * @param int[] $postIDs
     * @return array<string,mixed>
     */
    protected function getFakePostDataEntries(array $postIDs = []): array
    {
        $wpPosts = $this->getPostFixedDataset();
        if ($postIDs !== []) {
            array_filter(
                $wpPosts,
                fn (array $wpPost) => in_array($wpPost['post_id'], $postIDs)
            );
        }

        // $properties = [
        //     'post_title',
        //     'guid',
        //     'post_author',
        //     'post_content',
        //     'post_excerpt',
        //     'post_id',
        //     'post_date',
        //     'post_date_gmt',
        //     'comment_status',
        //     'ping_status',
        //     'post_name',
        //     'status',
        //     'post_parent',
        //     'menu_order',
        //     'post_type',
        //     'post_password',
        //     'is_sticky',
        //     'terms',
        // ];
        
        /**
         * Convert "post_id" to "id", keep all other properties the same
         */
        return array_map(
            fn (array $wpPost) => [
                ...$wpPost,
                ...[
                    'id' => $wpPost['author_id'],
                ]
            ],
            $wpPosts
        );
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getPostFixedDataset(): array
    {
        return $this->getDataProvider()->getFixedDataset()['posts'] ?? [];
    }

    /**
     * @param int[] $postIDs
     * @return WP_Post[]
     */
    protected function getFakePosts(array $postIDs): array
    {
        return array_map(
            fn (array $fakePostDataEntry) => App::getWPFaker()->post($fakePostDataEntry),
            $this->getFakePostDataEntries($postIDs)
        );
    }
}
