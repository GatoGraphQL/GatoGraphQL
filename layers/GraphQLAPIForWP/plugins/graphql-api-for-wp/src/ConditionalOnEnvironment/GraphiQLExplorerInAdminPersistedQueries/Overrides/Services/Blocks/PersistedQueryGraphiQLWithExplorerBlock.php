<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\GraphiQLExplorerInAdminPersistedQueries\Overrides\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\PersistedQueryGraphiQLBlock;

/**
 * GraphiQL with Explorer block
 */
class PersistedQueryGraphiQLWithExplorerBlock extends PersistedQueryGraphiQLBlock
{
    use GraphiQLWithExplorerBlockTrait;
}
