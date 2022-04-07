<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\UsersWP\TypeAPIs;

use GraphQLAPI\WPFakerSchema\App;
use GraphQLAPI\WPFakerSchema\Component;
use GraphQLAPI\WPFakerSchema\ComponentConfiguration;
use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI as UpstreamUserTypeAPI;
use WP_User;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI extends UpstreamUserTypeAPI
{
    private ?DataProviderInterface $dataProvider = null;

    final public function setDataProvider(DataProviderInterface $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }
    final protected function getDataProvider(): DataProviderInterface
    {
        return $this->dataProvider ??= $this->instanceManager->getInstance(DataProviderInterface::class);
    }

    protected function getUsersByCMS(array $query): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useFixedDataset = $componentConfiguration->useFixedDataset();

        /**
         * If providing the IDs to retrieve, re-generate exactly those objects.
         */
        $ids = $query['include'] ?? null;
        if (!empty($ids)) {
            /** @var int[] */
            $userIDs = is_string($ids) ? explode(',', $ids) : $ids;
            $users = $this->getFakeUsers($userIDs);
            return $this->maybeRetrieveUserIDs($users, $query);
        }
        
        /**
         * Get users from the fixed dataset?
         */
        if ($useFixedDataset) {
            $userIDs = array_map(
                fn (array $wpAuthor) => $wpAuthor['author_id'],
                array_slice(
                    $this->getDataProvider()['authors'],
                    $query['offset'] ?? 0,
                    $query['number'] ?? 10
                )
            );
            $users = $this->getFakeUsers($userIDs);
            return $this->maybeRetrieveUserIDs($users, $query);
        }
        
        /**
         * Otherwise, let BrainFaker produce random entries
         */
        $users = App::getWPFaker()->users($query['number'] ?? 10);
        return $this->maybeRetrieveUserIDs($users, $query);
    }

    /**
     * Retrieve the IDs of the objects?
     *
     * @param WP_User[] $users
     * @return WP_User[]|int[]
     */
    protected function maybeRetrieveUserIDs(array $users, array $query): array
    {
        if (($query['fields'] ?? null) === 'ID') {
            return array_map(
                fn (WP_User $user) => $user->ID,
                $users
            );
        }
        return $users;
    }

    /**
     * @param int[] $userIDs
     * @return WP_User[]
     */
    protected function getFakeUsers(array $userIDs): array
    {
        return array_map(
            fn (array $fakeUserData) => App::getWPFaker()->user($fakeUserData),
            $this->getFakeDataForUsers($userIDs)
        );
    }

    /**
     * @param int[] $userIDs
     * @return array<string,mixed>
     */
    protected function getFakeDataForUsers(array $userIDs): array
    {
        $wpAuthors = array_filter(
            $this->getDataProvider()['authors'],
            fn (array $wpAuthor) => in_array($wpAuthor['author_id'], $userIDs)
        );
        return array_map(
            fn (array $wpAuthor) => [
                'id' => $wpAuthor['author_id'],
                'login' => $wpAuthor['author_login'],
                'email' => $wpAuthor['author_email'],
                'display_name' => $wpAuthor['author_display_name'],
                'first_name' => $wpAuthor['author_first_name'],
                'last_name' => $wpAuthor['author_last_name'],
            ],
            $wpAuthors
        );
    }
}
