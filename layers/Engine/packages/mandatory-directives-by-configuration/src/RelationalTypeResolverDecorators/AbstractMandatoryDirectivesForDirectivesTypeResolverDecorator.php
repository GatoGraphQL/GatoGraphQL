<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;

abstract class AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    use ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;

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
