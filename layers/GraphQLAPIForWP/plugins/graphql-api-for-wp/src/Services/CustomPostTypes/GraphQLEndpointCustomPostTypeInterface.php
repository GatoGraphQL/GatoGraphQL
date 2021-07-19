<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use WP_Post;

interface GraphQLEndpointCustomPostTypeInterface extends CustomPostTypeInterface
{
    public function getEndpointOptionsBlock(): AbstractBlock;

    /**
     * Read the options block and check the value of attribute "isEndpointEnabled"
     */
    public function isEndpointEnabled(WP_Post|int $postOrID): bool;

    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    public function getOptionsBlockDataItem(WP_Post|int $postOrID): ?array;
}
