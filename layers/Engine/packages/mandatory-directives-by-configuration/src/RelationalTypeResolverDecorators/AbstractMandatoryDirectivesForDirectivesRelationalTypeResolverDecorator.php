<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;

abstract class AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    use ConfigurableMandatoryDirectivesForDirectivesRelationalTypeResolverDecoratorTrait;

    /**
     * By default, it is valid everywhere
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }
}
