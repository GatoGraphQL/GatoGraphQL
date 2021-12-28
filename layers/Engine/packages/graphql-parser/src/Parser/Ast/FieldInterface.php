<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\FieldInterface as UpstreamFieldInterface;

interface FieldInterface extends UpstreamFieldInterface
{
    public function isSkipOutputIfNull(): bool;
}
