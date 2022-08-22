<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    use ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;

    /**
     * By default, it is valid everywhere
     * @return array<class-string<RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
