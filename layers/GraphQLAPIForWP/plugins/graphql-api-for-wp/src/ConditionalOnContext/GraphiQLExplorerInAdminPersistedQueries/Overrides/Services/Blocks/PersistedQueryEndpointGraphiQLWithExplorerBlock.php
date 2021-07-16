<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\GraphiQLExplorerInAdminPersistedQueries\Overrides\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;

/**
 * GraphiQL with Explorer block
 */
class PersistedQueryEndpointGraphiQLWithExplorerBlock extends PersistedQueryEndpointGraphiQLBlock
{
    use GraphiQLWithExplorerBlockTrait;
}
