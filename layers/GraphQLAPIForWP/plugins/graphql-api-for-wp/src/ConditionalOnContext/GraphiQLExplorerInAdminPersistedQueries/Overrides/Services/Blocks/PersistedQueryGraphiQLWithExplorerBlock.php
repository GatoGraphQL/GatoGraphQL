<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\GraphiQLExplorerInAdminPersistedQueries\Overrides\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryGraphiQLBlock;

/**
 * GraphiQL with Explorer block
 */
class PersistedQueryGraphiQLWithExplorerBlock extends PersistedQueryGraphiQLBlock
{
    use GraphiQLWithExplorerBlockTrait;
}
