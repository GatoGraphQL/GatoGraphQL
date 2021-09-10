<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;

abstract class AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator extends AbstractTypeResolverDecorator
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
