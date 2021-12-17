<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Field as UpstreamField;

class Field extends UpstreamField
{
    /**
     * Custom extension, needed for PoP's SiteBuilder
     */
    public function skipOutputIfNull(): bool
    {
        return false;
    }
}
