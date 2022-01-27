<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Runtime\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface as UpstreamFieldInterface;

interface FieldInterface extends UpstreamFieldInterface
{
    public function isSkipOutputIfNull(): bool;
}
