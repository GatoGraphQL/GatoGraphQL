<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Hooks;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Constants\QueryOptions;
use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPSchema\SchemaCommons\Constants\QueryOptions as SchemaCommonsQueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

class QueryHookSet extends AbstractHookSet
{
    public final const NON_EXISTING_ID = "non-existing";

    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;

    final public function setCustomPostTypeRegistry(CustomPostTypeRegistryInterface $customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        return $this->customPostTypeRegistry ??= $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            CustomPostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    /**
     * Remove querying private CPTs
     *
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function convertCustomPostsQuery(array $query, array $options): array
    {
        // Hooks must be active only when resolving the query into IDs,
        // and not when resolving IDs into object, since there we don't have `$options`
        if (
            isset($query['post_type'])
            && (!isset($options[QueryOptions::ALLOW_QUERYING_PRIVATE_CPTS]) || !$options[QueryOptions::ALLOW_QUERYING_PRIVATE_CPTS])
            && isset($options[SchemaCommonsQueryOptions::RETURN_TYPE]) && $options[SchemaCommonsQueryOptions::RETURN_TYPE] == ReturnTypes::IDS
        ) {
            /**
             * All CPTs from the GraphQL API plugin and its extensions
             * must not be queried from outside, since they are used for
             * configuration purposes only, which is private data.
             */
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            $query['post_type'] = array_diff(
                is_array($query['post_type']) ? $query['post_type'] : [$query['post_type']],
                array_map(
                    fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService->getCustomPostType(),
                    $customPostTypeServices
                )
            );
            // If there are no valid postTypes, then return no results
            // By not adding the post type, WordPress will fetch a "post"
            // Then, include a non-existing ID
            if (!$query['post_type']) {
                $query['include'] = self::NON_EXISTING_ID;
            }
        }
        return $query;
    }
}
