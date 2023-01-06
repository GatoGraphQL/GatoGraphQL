<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\DummySchema\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class ByeByeWorldVersion120FieldDirectiveResolver extends AbstractHelloWorldFieldDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'byeByeWorld';
    }

    public function getPriorityToAttachToClasses(): int
    {
        // Higher priority => Process earlier
        return 20;
    }

    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return '1.2.0';
    }
}
