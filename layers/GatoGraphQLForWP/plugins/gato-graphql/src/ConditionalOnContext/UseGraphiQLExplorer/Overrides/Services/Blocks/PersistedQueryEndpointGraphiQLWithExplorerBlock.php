<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;

/**
 * GraphiQL with Explorer block
 */
class PersistedQueryEndpointGraphiQLWithExplorerBlock extends PersistedQueryEndpointGraphiQLBlock
{
    use GraphiQLWithExplorerBlockTrait;
}
